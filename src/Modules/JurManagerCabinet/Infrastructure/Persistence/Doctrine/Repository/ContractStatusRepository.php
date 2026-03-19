<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractStatusRepositoryInterface;

class ContractStatusRepository implements ContractStatusRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getAllContractStatuses(): array
    {
        // TODO: Implement getAllContractStatuses() method.
    }

    /**
     * @inheritDoc
     */
    public function getContractStatusByContract(Contract $contract): ContractStatus
    {
        // TODO: Implement getContractStatusByContract() method.
    }

    /**
     * @inheritDoc
     */
    public function getContractStatusByContractId(int $id): ContractStatus
    {
        // TODO: Implement getContractStatusByContractId() method.
    }

    /**
     * @inheritDoc
     */
    public function changeContractStatus(Contract $contract, string $status): void
    {
        // TODO: Implement changeContractStatus() method.
    }
}