<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Entity\ProductService;
use App\Modules\UserCabinet\Repository\ProductServiceRepository;
use App\Modules\UserCabinet\Service\Dto\Request\ServiceFilterDto;

class ServService
{
    protected ProductServiceRepository $servRepo;
    public function __construct(ProductServiceRepository $servRepo)
    {
        $this->servRepo = $servRepo;
    }
    public function getActiveServs(ServiceFilterDto $filter = new ServiceFilterDto()): ?array
    {
        $filter->setVisibleStatus(true);

        $servs = $this->servRepo->getServices($filter);

        return $servs;
    }
}