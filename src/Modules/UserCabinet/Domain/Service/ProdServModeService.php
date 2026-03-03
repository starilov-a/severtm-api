<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;

class ProdServModeService
{
    public function __construct(private ProdServModeRepositoryInterface $repo) {}

    public function isJuridical(ProdServMode $mode): bool
    {
        return $this->repo->hasGroup($mode->getId(), 'customer_business');
    }
}