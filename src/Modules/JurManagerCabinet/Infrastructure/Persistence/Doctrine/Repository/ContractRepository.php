<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\CustomerInnRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;
use App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository\Mappers\ContractMapper;

class ContractRepository implements ContractRepositoryInterface
{
    public function __construct(
        protected UserRepository $userRepo,
        protected CustomerInnRepository $customerInnRepo
    ) {}

    public function find(int $id): ?Contract
    {
        $tableUser = $this->userRepo->find($id);

        if (is_null($tableUser)) {
            return null;
        }

        return ContractMapper::map($tableUser);
    }

    public function findAllByInn(string $inn): array
    {
        $customerInn = $this->customerInnRepo->findOneBy(['inn' => $inn]);
        if ($customerInn === null) {
            return [];
        }

        /* @var User $tableUsers */
        $tableUsers = $this->userRepo->findBy(['customerInn' => $customerInn]);
        $contracts = [];

        foreach ($tableUsers as $tableUser) {
            $contracts[] = ContractMapper::map($tableUser);
        }

        return $contracts;
    }

    public function archiveForReissue(Contract $contract): void
    {
        // TODO: Implement archiveForReissue() method.
    }
}
