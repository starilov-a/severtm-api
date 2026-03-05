<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function find(int $id): Customer;
}