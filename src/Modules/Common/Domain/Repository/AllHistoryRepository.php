<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\AllHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AllHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllHistory::class);
    }
}
