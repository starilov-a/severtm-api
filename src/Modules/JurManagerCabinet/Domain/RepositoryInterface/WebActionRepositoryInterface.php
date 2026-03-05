<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

interface WebActionRepositoryInterface
{
    public function findIdByCid(string $cid);
}