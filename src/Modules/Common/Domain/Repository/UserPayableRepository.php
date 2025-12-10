<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\UserPayable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPayable::class);
    }
}

