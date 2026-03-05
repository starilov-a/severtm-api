<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPayableParameter::class);
    }

    public function save(UserPayableParameter $userPayableParameter): UserPayableParameter
    {
        $this->getEntityManager()->persist($userPayableParameter);
        $this->getEntityManager()->flush();

        return $userPayableParameter;
    }
}

