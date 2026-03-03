<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\ProductService;

interface HasProductService
{
    public function getProductService(): ProductService;
}