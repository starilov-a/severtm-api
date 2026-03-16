<?php

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;

interface ContractStatusRepositoryInterface
{
    /**
     * @return ContractStatus[]
     */
    public function getAllContractStatuses(): array;

    /**
     * @var Contract $contract
     *
     * @return ContractStatus
     */
    public function getContractStatusByContract(Contract $contract): ContractStatus;

    /**
     * @var int $id
     *
     * @return ContractStatus
     */
    public function getContractStatusByContractId(int $id): ContractStatus;

    /**
     * @var Contract $contract
     * @var string $status
     */
    public function changeContractStatus(Contract $contract, string $status): void;
}