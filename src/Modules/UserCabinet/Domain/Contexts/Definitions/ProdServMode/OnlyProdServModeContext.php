<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\ProdServMode;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;

class OnlyProdServModeContext implements HasProdServMode
{
    public function __construct(
        protected ProdServMode $prodServMode
    ) {}

    public function getMode(): ProdServMode
    {
        return $this->prodServMode;
    }
}