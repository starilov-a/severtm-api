<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;

class ProdServModeService
{
    public function __construct(private ProdServModeRepositoryInterface $repo) {}

    public function isJuridical(ProdServMode $mode): bool
    {
        return $this->repo->hasGroup($mode->getId(), 'customer_business');
    }
}