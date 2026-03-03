<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\Entity\ProdServModeCost;

interface ProdServModeCostRepositoryInterface extends RepositoryInterface
{
    public function findOneByModeAndCostModeId(ProdServMode $mode, int $costModeId): ?ProdServModeCost;
}
