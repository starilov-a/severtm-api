<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Dto\Request\ServModeFilterDto;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;

class ServModeService
{
    public function __construct(
        protected ProdServModeRepositoryInterface $servModeRepo,
    ) {}
    /*
     * return @ProdServMode[]
     * */
    public function getActiveModes(ServModeFilterDto $filter = new ServModeFilterDto()): ?array
    {
        return $this->servModeRepo->getModeByFilter($filter);
    }
}