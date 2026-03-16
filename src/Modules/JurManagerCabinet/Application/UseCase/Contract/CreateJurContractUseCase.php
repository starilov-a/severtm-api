<?php

namespace App\Modules\JurManagerCabinet\Application\UseCase\Contract;

use App\Modules\JurManagerCabinet\Application\Dto\Request\CreateJurContractDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractCreationRepositoryInterface;

class CreateJurContractUseCase
{
    public function __construct(
        private readonly ContractCreationRepositoryInterface $contractCreationRepository,
    ) {}

    public function execute(CreateJurContractDto $dto): Contract
    {
        return $this->contractCreationRepository->create(
            $dto->getInn(),
            $dto->getFullName(),
            $dto->getLogin(),
            $dto->getPassword(),
            $dto->getEmail(),
            $dto->getPhone(),
            $dto->isJuridical(),
        );
    }
}