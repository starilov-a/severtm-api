<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\UserPayableType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPayableType::class);
    }

    public function findOneByCode(string $code): ?UserPayableType
    {
        return $this->findOneBy(['code' => $code]);
    }
}

