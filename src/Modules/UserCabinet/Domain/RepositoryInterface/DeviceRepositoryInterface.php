<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Device;

interface DeviceRepositoryInterface extends RepositoryInterface
{
    public function findOneBySerialAndType(string $serialNumber, ?int $deviceTypeId): ?Device;
    public function save(Device $device): Device;
}
