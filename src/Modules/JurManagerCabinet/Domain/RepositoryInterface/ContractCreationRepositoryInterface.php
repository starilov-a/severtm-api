<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;

interface ContractCreationRepositoryInterface
{
    public function create(CreateJurContractDto $contractDto): Contract;
}