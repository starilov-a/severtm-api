<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;

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