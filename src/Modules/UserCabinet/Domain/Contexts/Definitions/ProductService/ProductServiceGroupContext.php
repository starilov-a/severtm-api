<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\ProductService;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProductService;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasPsGroup;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\ProductService;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\PsGroup;

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