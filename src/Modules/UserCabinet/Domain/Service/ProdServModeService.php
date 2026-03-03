<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\Repository\ProdServModeRepository;

class ProdServModeService
{
    public function __construct(private ProdServModeRepository $repo) {}

    public function isJuridical(ProdServMode $mode): bool
    {
        return $this->repo->hasGroup($mode->getId(), 'customer_business');
    }
}