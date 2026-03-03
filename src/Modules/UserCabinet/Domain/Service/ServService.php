<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Repository\ProductServiceRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServiceFilterDto;

class ServService
{
    protected ProductServiceRepository $servRepo;
    public function __construct(ProductServiceRepository $servRepo)
    {
        $this->servRepo = $servRepo;
    }
    public function getActiveServs(?ServiceFilterDto $filter = new ServiceFilterDto()): ?array
    {
        $filter->setVisibleStatus(true);

        $servs = $this->servRepo->getServicesByFilter($filter);

        return $servs;
    }
}