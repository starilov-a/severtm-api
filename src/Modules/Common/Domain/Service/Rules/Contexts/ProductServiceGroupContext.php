<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\ProductService;
use App\Modules\Common\Domain\Entity\PsGroup;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProductService;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPsGroup;

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