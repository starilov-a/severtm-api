<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Repository\ProductServiceRepository;
use App\Modules\Common\Domain\Service\Dto\Request\ServiceFilterDto;

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