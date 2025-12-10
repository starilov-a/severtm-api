<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;


use App\Modules\Common\Domain\Service\Dto\Dto;
use App\Modules\Common\Infrastructure\Traits\Dto\PaginationTrait;
use App\Modules\Common\Infrastructure\Traits\Dto\SortTrait;

class FilterDto extends Dto
{
    use PaginationTrait;
    use SortTrait;
}