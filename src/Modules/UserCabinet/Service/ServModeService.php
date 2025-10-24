<?php

namespace App\Modules\UserCabinet\Service;

use App\Modules\UserCabinet\Entity\ProductService;
use App\Modules\UserCabinet\Service\Dto\Request\ServModeFilterDto;

class ServModeService
{
    /*
     * return @ProdServMode[]
     * */
    public function getActiveModes(ProductService $serv, ServModeFilterDto $filter = new ServModeFilterDto()): ?array
    {

        return [];
    }
}