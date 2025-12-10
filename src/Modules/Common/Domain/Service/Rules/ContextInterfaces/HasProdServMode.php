<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\ProdServMode;

interface HasProdServMode
{
    public function getMode(): ProdServMode;
}