<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServModeCost;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;

interface ProdServModeCostRepositoryInterface extends RepositoryInterface
{
    public function findOneByModeAndCostModeId(ProdServMode $mode, int $costModeId): ?ProdServModeCost;
}
