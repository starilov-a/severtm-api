<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract;

interface ContractRepositoryInterface
{
    public function find(int $id): Contract;

    public function findAllByInn(string $inn): array;

    public function archiveForReissue(Contract $contract): void;
}