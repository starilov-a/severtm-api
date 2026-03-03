<?php

namespace App\Modules\UserCabinet\Application\UseCase\ProdServMode;

use App\Modules\UserCabinet\Domain\Entity\UserServMode;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Repository\WebActionRepository;
use App\Modules\UserCabinet\Domain\Service\UserOwnDeviceService;
use App\Modules\UserCabinet\Domain\Service\UserServModeService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

class DisableServiceModeUseCase
{
    public function __construct(
        protected WebActionRepository       $webActionRepo,
        protected UserRepository            $userRepo,

        protected LoggerService             $loggerService,
        protected UserOwnDeviceService      $userOwnDeviceService,
        protected UserServModeService       $userServModeService,
    ) {}

    /**
     * Workflow: Отключение активной опции
     *
     * 1. Выставление статуса isActive=false
     * 2. Отвязка устройств
     * 3. Сохранение изменений
     *
     * @param UserServMode $userServMode
     * @return void
     */
    public function handle(UserServMode $userServMode): void
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());
        $webAction = $this->webActionRepo->findIdByCid('WA_USERS_DELETE_SERVICE');

        $userServMode->setIsActive(false);
        //Отвязка устройства
        if ($userServMode->getDevice())
            $this->userOwnDeviceService->removeDeviceFromUser($userServMode->getDevice());

        $this->userServModeService->save($userServMode);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Услуга('.$userServMode->getMode()->getName().',usmid:'.$userServMode->getId().' ) 
            отключена у пользователя: ' . $userServMode->getUser()->getId(),
            true
        ));
    }
}