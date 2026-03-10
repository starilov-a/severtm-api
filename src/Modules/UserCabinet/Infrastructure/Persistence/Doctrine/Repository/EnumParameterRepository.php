<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\EnumParameterRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\EnumParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EnumParameterRepository extends ServiceEntityRepository implements EnumParameterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnumParameter::class);
    }
}

