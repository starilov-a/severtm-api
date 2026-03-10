<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeCostRepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServModeCost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProdServModeCostRepository extends ServiceEntityRepository implements ProdServModeCostRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdServModeCost::class);
    }

    public function findOneByModeAndCostModeId(ProdServMode $mode, int $costModeId): ?ProdServModeCost
    {
        return $this->findOneBy([
            'mode'       => $mode,
            'costModeId' => $costModeId,
        ]);
    }
}

