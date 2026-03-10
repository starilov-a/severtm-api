<?php

namespace App\Modules\UserCabinet\Application\UseCase\ProdServMode;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\UserOwnDeviceService;
use App\Modules\UserCabinet\Domain\Service\UserServModeService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class DisableServiceModeUseCase
{
    public function __construct(
        protected WebActionRepositoryInterface       $webActionRepo,
        protected UserRepositoryInterface            $userRepo,
        protected UserServModeRepositoryInterface    $userServModeRepo,

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

        $this->userServModeRepo->save($userServMode);

        $this->loggerService->businessLog(new BusinessLogDto(
            $master->getId(),
            $webAction->getId(),
            'Услуга('.$userServMode->getMode()->getName().',usmid:'.$userServMode->getId().' ) 
            отключена у пользователя: ' . $userServMode->getUser()->getId(),
            true
        ));
    }
}