<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserOwnDeviceRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserOwnDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserOwnDeviceRepository extends ServiceEntityRepository implements UserOwnDeviceRepositoryInterface
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

