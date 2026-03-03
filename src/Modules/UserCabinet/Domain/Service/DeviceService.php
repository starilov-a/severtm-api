<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\Device;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Repository\DeviceRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\DeviceDto;

class DeviceService
{
    public function __construct(
        protected DeviceRepository $repo,
        protected UserOwnDeviceService $userOwnDeviceService
    ){}

    public function addOrCreateForUser(User $user, DeviceDto $deviceDto)
    {
        // 1. Создание нового Устройства
        $device = $this->repo->find($deviceDto->getId());

        if (!$device) {
            $device =  new Device;

            // TODO: Бизнес логика создания если есть

            //Наполнение
            $device->setSerialNumber($deviceDto->getSerialNumber());
            $device = $this->save($device);
        }

        // 2. Привязка устройства к Пользователю
        $this->userOwnDeviceService->attachDeviceToUser($user, $device, $deviceDto->getComment());

        return $device;
    }


    protected function save(Device $device)
    {
        $em = $this->repo->getEntityManager();
        $em->persist($device);
        $em->flush();

        return $device;
    }
}
