<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\EnumParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EnumParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnumParameter::class);
    }
}

