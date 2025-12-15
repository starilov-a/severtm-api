<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\UserOwnDeviceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserOwnDeviceHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnDeviceHistory::class);
    }
}

