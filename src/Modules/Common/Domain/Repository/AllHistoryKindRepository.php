<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\AllHistoryKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AllHistoryKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllHistoryKind::class);
    }
}
