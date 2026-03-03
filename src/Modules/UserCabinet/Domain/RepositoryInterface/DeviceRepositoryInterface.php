<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Device;

interface DeviceRepositoryInterface extends RepositoryInterface
{
    public function findOneBySerialAndType(string $serialNumber, ?int $deviceTypeId): ?Device;
    public function save(Device $device): Device;
}
