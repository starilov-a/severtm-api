<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\ProdServMode;

interface HasProdServMode
{
    public function getMode(): ProdServMode;
}