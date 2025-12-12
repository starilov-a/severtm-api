<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\UserPayableParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPayableParameter::class);
    }
}

