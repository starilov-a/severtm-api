<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\ProdServMode;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\UserCabinet\Domain\Entity\ProdServMode;

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