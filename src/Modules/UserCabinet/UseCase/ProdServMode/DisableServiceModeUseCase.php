<?php

namespace App\Modules\UserCabinet\UseCase\ProdServMode;

use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\WebActionRepository;
use App\Modules\Common\Domain\Service\UserOwnDeviceService;
use App\Modules\Common\Domain\Service\UserServModeService;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

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