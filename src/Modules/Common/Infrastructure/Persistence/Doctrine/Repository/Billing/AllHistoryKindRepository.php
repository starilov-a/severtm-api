<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;


use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\AllHistoryKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AllHistoryKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllHistoryKind::class);
    }
}
