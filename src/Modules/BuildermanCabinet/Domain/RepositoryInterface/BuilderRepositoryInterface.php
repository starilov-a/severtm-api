<?php

namespace App\Modules\BuildermanCabinet\Domain\RepositoryInterface;

use App\Modules\BuildermanCabinet\Domain\Entity\Builder;

interface BuilderRepositoryInterface
{
    public function findById(int $id): Builder;
}