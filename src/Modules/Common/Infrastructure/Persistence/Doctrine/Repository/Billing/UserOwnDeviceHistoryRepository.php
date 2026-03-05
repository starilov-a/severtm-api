<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserOwnDeviceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserOwnDeviceHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnDeviceHistory::class);
    }

    public function save(UserOwnDeviceHistory $userOwnDeviceHistory): UserOwnDeviceHistory
    {
        $this->getEntityManager()->persist($userOwnDeviceHistory);
        $this->getEntityManager()->flush();

        return $userOwnDeviceHistory;
    }
}

