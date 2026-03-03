<?php

namespace App\Modules\UserCabinet\Domain\Service\Dto\Request;


use App\Modules\UserCabinet\Domain\Service\Dto\Dto;
use App\Modules\UserCabinet\Infrastructure\Traits\Dto\PaginationTrait;
use App\Modules\UserCabinet\Infrastructure\Traits\Dto\SortTrait;

class FilterDto extends Dto
{
    use PaginationTrait;
    use SortTrait;
}