<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserOwnDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserOwnDeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnDevice::class);
    }

    public function save(UserOwnDevice $userOwnDevice): UserOwnDevice
    {
        $this->getEntityManager()->persist($userOwnDevice);
        $this->getEntityManager()->flush();

        return $userOwnDevice;
    }

    public function remove(UserOwnDevice $userOwnDevice): void
    {
        $this->getEntityManager()->remove($userOwnDevice);
        $this->getEntityManager()->flush();
    }
}

