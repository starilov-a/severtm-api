<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Repository\ProdServModeRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\ServModeFilterDto;

class ServModeService
{
    public function __construct(
        protected ProdServModeRepository $servModeRepo,
    ) {}
    /*
     * return @ProdServMode[]
     * */
    public function getActiveModes(ServModeFilterDto $filter = new ServModeFilterDto()): ?array
    {
        return $this->servModeRepo->getModeByFilter($filter);
    }
}