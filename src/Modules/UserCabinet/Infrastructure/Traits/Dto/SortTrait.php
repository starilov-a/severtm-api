<?php

namespace App\Modules\UserCabinet\Infrastructure\Traits\Dto;

trait SortTrait
{
    protected string $orderBy = 'id';
    protected string $orderDir = 'DESC';
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }
    public function getOrderDir(): string
    {
        return $this->orderDir;
    }
    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }
    public function setOrderDir(string $orderDir): self
    {
        $this->orderDir = $orderDir;
        return $this;
    }
}