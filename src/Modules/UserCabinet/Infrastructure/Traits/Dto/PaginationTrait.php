<?php

namespace App\Modules\UserCabinet\Infrastructure\Traits\Dto;

trait PaginationTrait
{
    protected int $limit = 20;
    protected int $offset = 0;

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
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