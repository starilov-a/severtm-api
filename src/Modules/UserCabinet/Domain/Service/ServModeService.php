<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdServModeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServModeFilterDto;

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