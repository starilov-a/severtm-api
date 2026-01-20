<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\ProductService;

interface HasProductService
{
    public function getProductService(): ProductService;
}