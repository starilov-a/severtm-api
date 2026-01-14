<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

use App\Modules\Common\Domain\Entity\ProductService;

interface HasProductService
{
    public function getProductService(): ProductService;
}