<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Repository\ProdServModeRepository;
use App\Modules\Common\Domain\Service\Dto\Request\ServModeFilterDto;

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