<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\AllHistoryKindRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\AllHistoryKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AllHistoryKindRepository extends ServiceEntityRepository implements AllHistoryKindRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllHistoryKind::class);
    }
}
