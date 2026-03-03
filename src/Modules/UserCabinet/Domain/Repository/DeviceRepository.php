<?php

namespace App\Modules\UserCabinet\Domain\Repository;

use App\Modules\UserCabinet\Domain\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /**
     * Поиск устройства по серийному номеру и типу.
     */
    public function findOneBySerialAndType(string $serialNumber, ?int $deviceTypeId): ?Device
    {
        return $this->findOneBy([
            'serialNumber' => $serialNumber,
            'deviceTypeId' => $deviceTypeId,
        ]);
    }
}

