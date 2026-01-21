<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserOwnDevice;
use App\Modules\Common\Domain\Repository\UserOwnDeviceRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UserOwnDeviceService
{
    public function __construct(
        protected UserOwnDeviceRepository $userOwnDeviceRepo,
        protected UserRepository $userRepo,
        protected LoggerService $loggerService,
        protected UserOwnDeviceHistoryService $userOwnDeviceHistoryRepo,
    ) {
    }

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

        return $this->save($own);
    }

    public function removeDeviceFromUser(Device $device, $comment = ''): void
    {
        //TODO: указать экшен
        $deviceOwn = $device->getOwnDevice();
        $owner = $deviceOwn->getUser();

        $this->delete($deviceOwn);

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

    protected function save(UserOwnDevice $own): UserOwnDevice
    {
        $em = $this->userOwnDeviceRepo->getEntityManager();
        $em->persist($own);
        $em->flush();

        return $own;
    }

    protected function delete(UserOwnDevice $own): void
    {
        $em = $this->userOwnDeviceRepo->getEntityManager();
        $em->remove($own);
        $em->flush();
    }
}

