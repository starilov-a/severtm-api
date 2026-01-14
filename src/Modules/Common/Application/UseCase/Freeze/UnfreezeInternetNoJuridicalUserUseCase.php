<?php

namespace App\Modules\Common\Application\UseCase\Freeze;

use App\Modules\Common\Application\UseCase\Tariff\ChangeCurrentTariffUseCase;
use App\Modules\Common\Application\UseCase\Tariff\ChangeNextTariffUseCase;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Repository\BlockHistoryRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\BlockHistoryService;
use App\Modules\Common\Domain\Service\Definitions\Finances\UserPaymentsService;
use App\Modules\Common\Domain\Service\Rules\Chains\Freeze\UnfreezeUserChain;
use App\Modules\Common\Domain\Service\Rules\Contexts\UserContext;
use App\Modules\Common\Domain\Service\TariffService;
use App\Modules\Common\Domain\Service\TaskService;
use App\Modules\Common\Domain\Service\UserService;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UnfreezeInternetNoJuridicalUserUseCase
{
    public function __construct(
        protected LoggerService                 $loggerService,
        protected TaskService                   $taskService,
        protected TariffService                 $tariffService,
        protected BlockHistoryService           $blockHistoryService,
        protected UserPaymentsService           $userPaymentsService,
        protected UserService                   $userService,

        protected ChangeCurrentTariffUseCase    $changeCurrentTariffUseCase,
        protected ChangeNextTariffUseCase       $changeNextTariffUseCase,

        protected UserTaskStateRepository       $taskStateRepo,
        protected UserTaskRepository            $userTaskRepo,
        protected UserRepository                $userRepo,
        protected WebActionRepository           $webActionRepo,
        protected BlockHistoryRepository        $blockHistoryRepo,

        protected UnfreezeUserChain             $unfreezeUserChain,

    ) {}
    /**
     * разморозка интернета физических лиц (без voip) (по процедуре __sys_unblock_recalc_tm)
     *
     * @param User $user
     * @return UserTask
     */
    public function handle(User $user): bool
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('W3_FIRST_UNFREEZE_ACCOUNT');

        // Бизнес проверки для возможности разморозки
        $this->unfreezeUserChain->checkAll(new UserContext($webAction, $master, $user));

        // обновляем таску на заморозку если есть активная - делаем неактивной
        $task = $this->userTaskRepo->findOneBy([
            'user' => $user,
            'state' => $this->taskStateRepo->findOneBy(['code' => 'freeze'])
        ]);

        if($task)
            $task = $this->taskService->updateUserTaskForCancel($task);

        // Перед рефаундом получаем актуальный тариф, который подключим после разморозки
        $tariffForActivate = $user->getCurrentTariff();

        //Делаем рефаунд по последнему активном user_serv_mode
        $lastActiveTariffUserServModes = $this->tariffService->getLastActiveUserServModesByUser($user);
        $this->userPaymentsService->refundForServiceMode($lastActiveTariffUserServModes, "Возврат за неиспользуемые дни");

        // Проходим по всем активным не отключенным интернет тарифам
        $tariffUserServModes = $this->tariffService->getActiveUserServModesByUser($user);
        foreach ($tariffUserServModes as $userServMode) {
            // Отключаем тарифы
            // TODO: в дальнейшем их вообще не должно быть
            $this->tariffService->disableTariffByUserServMode($userServMode);
        }

        // Отключаем информацию о тарифах(текущим и следующим) у пользователя
        $this->userService->disconnectedTariffs($user);

        // включаем интернет на текущий(+ списание)
        $this->changeCurrentTariffUseCase->handle($user, $tariffForActivate);

        // Обновляем историю блокировки
        $this->blockHistoryService->writeBlockLog($user);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Пользователь' . $user->getId() . ' успешно разморожен!.',
            true
        ));

        return true;
    }
}