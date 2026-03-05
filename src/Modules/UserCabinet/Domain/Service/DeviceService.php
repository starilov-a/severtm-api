<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Dto\Request\DeviceDto;
use App\Modules\UserCabinet\Domain\Entity\Device;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\DeviceRepositoryInterface;

class DeviceService
{
    public function __construct(
        protected DeviceRepositoryInterface $repo,
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
            $device = $this->repo->save($device);
        }

        // 2. Привязка устройства к Пользователю
        $this->userOwnDeviceService->attachDeviceToUser($user, $device, $deviceDto->getComment());

        return $device;
    }
}
