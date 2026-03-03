<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserPayable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableRepository extends ServiceEntityRepository implements UserPayableRepositoryInterface
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

