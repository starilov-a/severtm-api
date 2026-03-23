<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\BlockStateRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\ContractStatus;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractStatusRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;

class ContractStatusRepository implements ContractStatusRepositoryInterface
{

    public function __construct(
        protected UserRepository        $userRepo,
        protected BlockStateRepository  $blockStateRepo,
    ) {}

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
        $tableUser = $this->userRepo->find($contract->getId());

        $tableBlockStatus = $this->blockStateRepo->findByCode($status);

        $tableUser->setBlockState($tableBlockStatus);

        $this->userRepo->save($tableUser);
    }
}