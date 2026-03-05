<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Customer;

interface ContractRepositoryInterface
{
    public function find(int $id): Contract;

    public function findAllByInn(string $inn): array;
}