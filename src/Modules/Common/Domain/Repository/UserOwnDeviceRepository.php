<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\UserOwnDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserOwnDeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnDevice::class);
    }
}

