<?php

namespace App\Modules\UserCabinet\Service\Dto;

class FilterDto
{
    private $limit ;
    private $offset;

    public function __construct(int|null $limit, int|null $offset)
    {
        $this->limit = $limit ?? 30;
        $this->offset = $offset ?? 0;
    }
    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}