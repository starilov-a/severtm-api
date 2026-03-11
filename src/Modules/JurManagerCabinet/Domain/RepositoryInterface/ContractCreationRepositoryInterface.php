<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract;

interface ContractCreationRepositoryInterface
{
    public function create(
        string $inn,
        string $fullName,
        string $login,
        string $password,
        string $email,
        string $phone,
        bool $isJuridical,
    ): Contract;
}