<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProdServMode;

interface HasProdServMode
{
    public function getMode(): ProdServMode;
}