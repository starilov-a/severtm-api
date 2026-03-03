<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableTypeRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserPayableType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableTypeRepository extends ServiceEntityRepository implements UserPayableTypeRepositoryInterface
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

