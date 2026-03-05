<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Contract;

use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract;

class CreateJurContractUseCase
{
    public function __construct()
    {

    }

    public function handle(CreateJurContractDto $createJurContractDto): Contract
    {

    }
}