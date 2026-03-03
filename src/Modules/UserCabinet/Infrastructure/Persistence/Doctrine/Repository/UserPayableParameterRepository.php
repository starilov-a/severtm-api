<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserPayableParameterRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserPayableParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPayableParameterRepository extends ServiceEntityRepository implements UserPayableParameterRepositoryInterface
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

