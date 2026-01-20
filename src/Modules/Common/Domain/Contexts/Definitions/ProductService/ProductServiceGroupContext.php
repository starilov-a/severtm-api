<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\ProductService;

use App\Modules\Common\Domain\Contexts\Interfaces\HasProductService;
use App\Modules\Common\Domain\Contexts\Interfaces\HasPsGroup;
use App\Modules\Common\Domain\Entity\ProductService;
use App\Modules\Common\Domain\Entity\PsGroup;

class ProductServiceGroupContext implements HasProductService, HasPsGroup
{

    public function __construct(
        protected ProductService $productService,
        protected PsGroup $psGroup
    ) {}

    public function getProductService(): ProductService
    {
        return $this->productService;
    }

    public function getPsGroup(): PsGroup
    {
        return $this->psGroup;
    }
}