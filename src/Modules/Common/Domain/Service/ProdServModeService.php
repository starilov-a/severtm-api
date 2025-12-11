<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Repository\ProdServModeRepository;

class ProdServModeService
{
    public function __construct(private ProdServModeRepository $repo) {}

    public function isJuridical(ProdServMode $mode): bool
    {
        return $this->repo->hasGroup($mode->getId(), 'customer_business');
    }
}