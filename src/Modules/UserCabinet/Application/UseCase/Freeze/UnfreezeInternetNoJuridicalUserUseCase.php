<?php

namespace App\Modules\UserCabinet\Application\UseCase\Freeze;


use App\Modules\UserCabinet\Domain\Contexts\Definitions\User\UserContext;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserTask;
use App\Modules\UserCabinet\Domain\RepositoryInterface\BlockHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Chains\Freeze\UnfreezeUserChain;
use App\Modules\UserCabinet\Domain\Service\BlockHistoryService;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\UserPaymentsService;
use App\Modules\UserCabinet\Domain\Service\TariffService;
use App\Modules\UserCabinet\Domain\Service\TaskService;
use App\Modules\UserCabinet\Domain\Service\UserService;
use App\Modules\UserCabinet\Domain\Workflow\Tariff\ChangeCurrentTariffWorkflow;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class UnfreezeInternetNoJuridicalUserUseCase
{
    public function __construct(
        protected LoggerService                 $loggerService,
        protected TaskService                   $taskService,
        protected TariffService                 $tariffService,
        protected BlockHistoryService           $blockHistoryService,
        protected UserPaymentsService           $userPaymentsService,
        protected UserService                   $userService,

        protected ChangeCurrentTariffWorkflow    $changeCurrentTariffWorkflow,

        protected UserTaskTypeRepositoryInterface        $taskTypeRepo,
        protected UserTaskRepositoryInterface            $userTaskRepo,
        protected UserRepositoryInterface                $userRepo,
        protected WebActionRepositoryInterface           $webActionRepo,
        protected BlockHistoryRepositoryInterface        $blockHistoryRepo,

        protected UnfreezeUserChain             $unfreezeUserChain,

    ) {}
    /**
     * Workflow: разморозка интернета физических лиц
     *
     * 1. Бизнес проверки
     * 2. Поиск активной задачи на заморозку и её отмена
     * 3. Получаем тариф, который будет подключен после разморозки
     * 4. Поиск активного тарифа (*user_serv_mode*) на период блокировки и рефаунд до конца месяца того же фин периода
     * 5. Поиск и отключение всех предыдущих активных тарифов
     * 6. Workflow: Изменение текущего тарифа
     * 7. Запись о изменении статуса блокировки в историю блокировок (*BlockHistory*)
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
            'type' => $this->taskTypeRepo->findOneBy(['code' => 'freeze'])
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

        // включаем интернет на текущий(+ списание)
        $this->changeCurrentTariffWorkflow->handle($user, $tariffForActivate);

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