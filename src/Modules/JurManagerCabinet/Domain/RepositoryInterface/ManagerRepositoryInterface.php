<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

interface ManagerRepositoryInterface
{
    public function find(int $id);
}