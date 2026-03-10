<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProductService;

interface HasProductService
{
    public function getProductService(): ProductService;
}