<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\DeviceRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DeviceRepository extends ServiceEntityRepository implements DeviceRepositoryInterface
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

    public function save(Device $device): Device
    {
        $this->getEntityManager()->persist($device);
        $this->getEntityManager()->flush();

        return $device;
    }
}

