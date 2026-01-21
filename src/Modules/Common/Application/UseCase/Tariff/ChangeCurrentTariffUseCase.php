<?php

namespace App\Modules\Common\Application\UseCase\Tariff;

use App\Modules\Common\Application\UseCase\ProdServMode\AddCurrentServiceModeUseCase;
use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\TariffService;
use App\Modules\Common\Domain\Service\UserService;
use App\Modules\Common\Domain\Service\UserServModeService;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ChangeCurrentTariffUseCase
{
    public function __construct(
        protected UserRepository                $userRepo,
        protected FinPeriodRepository           $finPeriodRepo,
        protected WebActionRepository           $webActionRepo,
        protected BlockStateRepository          $blockStateRepo,

        protected LoggerService                 $loggerService,
        protected UserService                   $userService,
        protected UserServModeService           $userServModeService,
        protected TariffService                 $tariffService,

        protected AddCurrentServiceModeUseCase  $addCurrentServiceModeUseCase,
    ) {}

    /**
     * UseCase: Изменение текущего тарифа
     *
     * @param User $user
     * @param Tariff $newCurrentTariff
     * @return bool
     * @throws \Exception
     */
    public function handle(User $user, Tariff $newCurrentTariff): bool
    {
        $currentFinPeriod = $this->finPeriodRepo->getCurrent();
        $webAction = $this->webActionRepo->findIdByCid('SET_CURRENT_INET');
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // ДЕЙСТВИЯ
        // 1. Создать новый user_serv_mode - tariff (создание usm)
        $this->addCurrentServiceModeUseCase->handle($user, $newCurrentTariff->getProdServMode());

        // 2. Меняем в таблице Users тариф пользователя
        $this->tariffService->changeUserTariff($user, $newCurrentTariff, $currentFinPeriod);

        // 3. Изменяем информацию о дате начала и конца учетного периода (участвует только в интернете)
        $this->userService->updatingAbPeriod($user);

        // 4. Активируем пользователя
        $this->userService->updatingBlockState($user, $this->blockStateRepo->findByCode('unblocked'));

        // 5. запись в историю об успехе
        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Тариф на текущий месяц для пользователя ' . $user->getId() . ' успешно изменен - ' . $newCurrentTariff->getName(). '(' . $newCurrentTariff->getId() .')' ,
            true
        ));

        return true;
    }
}