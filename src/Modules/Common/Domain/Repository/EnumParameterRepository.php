<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\EnumParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EnumParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnumParameter::class);
    }
}

