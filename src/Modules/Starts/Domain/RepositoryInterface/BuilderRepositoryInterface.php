<?php

namespace App\Modules\Starts\Domain\RepositoryInterface;

use App\Modules\Starts\Domain\Entity\Builder;

interface BuilderRepositoryInterface
{
    public function find(int $id): Builder;
}