<?php

namespace App\Modules\UserCabinet\Domain\Dto\Request;


use App\Modules\Common\Application\Dto\Dto;
use App\Modules\Common\Infrastructure\Traits\Dto\PaginationTrait;
use App\Modules\Common\Infrastructure\Traits\Dto\SortTrait;

class FilterDto extends Dto
{
    use PaginationTrait;
    use SortTrait;
}