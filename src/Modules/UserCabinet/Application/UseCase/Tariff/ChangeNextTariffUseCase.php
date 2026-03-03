<?php

namespace App\Modules\UserCabinet\Application\UseCase\Tariff;


use App\Modules\UserCabinet\Application\UseCase\ProdServMode\AddNextServiceModeUseCase;
use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\TariffService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

/**
 *
 *
 */
class ChangeNextTariffUseCase
{
    public function __construct(
        protected UserRepositoryInterface      $userRepo,
        protected FinPeriodRepositoryInterface $finPeriodRepo,
        protected WebActionRepositoryInterface $webActionRepo,

        protected LoggerService                $loggerService,
        protected TariffService                $tariffService,

        protected AddNextServiceModeUseCase    $addNextServiceModeUseCase,
    ) {}

    /**
     * Workflow: Изменение следующего тарифа
     *
     *  1. Меняем тариф в таблице
     *  2. Чистим все будущие тарифы
     *  3. Workflow: Добавление user_serv_modes на след. месяц
     *
     * @param User $user
     * @param Tariff $newNextTariff
     * @return bool
     * @throws \Exception
     */
    public function handle(User $user, Tariff $newNextTariff): bool
    {
        $finPeriod = $this->finPeriodRepo->getNext();
        $webAction = $this->webActionRepo->findIdByCid('SET_NEXT_INET');
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // ДЕЙСТВИЯ
        // 1. Меняем в таблице Users тариф пользователя
        $this->tariffService->changeUserTariff($user, $newNextTariff, $finPeriod);

        // 2. Отключаем будущие user_serv_mode - tariff (удаление будущих usm)
        $this->tariffService->removeNextUserTariff($user);

        // 3. Создать новый user_serv_mode - tariff (создание usm) - используем оркестратор
        $this->addNextServiceModeUseCase->handle($user, $newNextTariff->getProdServMode());

        // 4. запись в историю об успехе
        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Тариф на следующий месяц для пользователя ' . $user->getId() . ' успешно изменен тариф - ' . $newNextTariff->getName(). '(' . $newNextTariff->getId() .')' ,
            true
        ));

        return true;
    }
}