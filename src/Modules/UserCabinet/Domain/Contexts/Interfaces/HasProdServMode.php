<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\ProdServMode;

interface HasProdServMode
{
    public function getMode(): ProdServMode;
}