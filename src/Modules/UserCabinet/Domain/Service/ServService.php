<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\RepositoryInterface\ProductServiceRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServiceFilterDto;

class ServService
{
    protected ProductServiceRepositoryInterface $servRepo;
    public function __construct(ProductServiceRepositoryInterface $servRepo)
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