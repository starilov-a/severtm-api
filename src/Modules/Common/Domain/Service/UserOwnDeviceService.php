<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserOwnDevice;
use App\Modules\Common\Domain\Repository\UserOwnDeviceRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class UserOwnDeviceService
{
    public function __construct(
        protected UserOwnDeviceRepository $userOwnDeviceRepo,
        protected UserRepository $userRepo,
    ) {
    }

    /**
     * Вариант: выделяем отдельный сервис и entity для user_own_devices,
     * чтобы повторить модель SQL-процедур и не грузить DeviceService лишней ответственностью.
     */
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

    protected function save(UserOwnDevice $own): UserOwnDevice
    {
        $em = $this->userOwnDeviceRepo->getEntityManager();
        $em->persist($own);
        $em->flush();

        return $own;
    }
}

