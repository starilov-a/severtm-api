<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Repository\DeviceRepository;
use App\Modules\Common\Domain\Service\Dto\Request\DeviceDto;

class DeviceService
{
    public function __construct(
        protected DeviceRepository $repo,
        protected UserOwnDeviceService $userOwnDeviceService
    ){}

    /**
     * Привязать устройство к конкретной активной услуге пользователя.
     *
     * 1) находим/создаём Device и связываем его с пользователем (user_own_devices);
     * 2) вешаем это устройство на переданный UserServMode.
     */
    public function attachDeviceToServiceMode(UserServMode $userServMode, Device $device): UserServMode
    {
        // привязываем устройство к режиму услуги
        $userServMode->setDevice($device);

        return $userServMode;
    }

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
