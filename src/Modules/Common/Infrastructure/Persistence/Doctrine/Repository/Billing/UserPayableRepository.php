<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPayable::class);
    }
    public function save(UserPayable $payable): UserPayable
    {
        $this->getEntityManager()->persist($payable);
        $this->getEntityManager()->flush();

        return $payable;
    }
}

