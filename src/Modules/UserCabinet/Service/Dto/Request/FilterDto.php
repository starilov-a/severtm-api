<?php

namespace App\Modules\UserCabinet\Service\Dto\Request;

use App\Modules\UserCabinet\Service\Dto\Dto;

class FilterDto extends Dto
{
    private int $limit ;
    private int $offset;

    /**
     * @param mixed $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
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