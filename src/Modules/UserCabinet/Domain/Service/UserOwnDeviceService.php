<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Entity\Device;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserOwnDevice;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserOwnDeviceRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class UserOwnDeviceService
{
    public function __construct(
        protected UserOwnDeviceRepositoryInterface $userOwnDeviceRepo,
        protected UserRepositoryInterface $userRepo,
        protected LoggerService $loggerService,
        protected UserOwnDeviceHistoryService $userOwnDeviceHistoryRepo,
    ) {}

    public function attachDeviceToUser(User $user, Device $device, $comment = ''): UserOwnDevice
    {
        $own = new UserOwnDevice();
        $master = $this->userRepo->find(UserSessionService::getUserId());

        // TODO: Бизнес логика если есть

        $own->setDevice($device);
        $own->setUser($user);
        $own->setMasterUid($master->getId());
        $own->setDeviceComment($comment);
        $own->setTimeStamp(new \DateTime());

        return $this->userOwnDeviceRepo->save($own);
    }

    public function removeDeviceFromUser(Device $device, $comment = ''): void
    {
        //TODO: указать экшен
        $deviceOwn = $device->getOwnDevice();
        $owner = $deviceOwn->getUser();

        $this->userOwnDeviceRepo->remove($deviceOwn);

        //Запись в историю изменений владельцев устройств
        $this->userOwnDeviceHistoryRepo->addHistoryLog($device, $owner, $comment, 'D');

        $this->loggerService->businessLog(new BusinessLogDto(
            $this->userRepo->find(UserSessionService::getUserId())->getId(),
            0,
            "Устройство({$device->getId()}:{$device->getSerialNumber()}) 
            отвязано от пользователя({$owner->getId()})",
            true
        ));
    }
}
