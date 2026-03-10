<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Action;

interface WebActionRepositoryInterface
{
    public function findIdByCid(string $cid): Action;
}