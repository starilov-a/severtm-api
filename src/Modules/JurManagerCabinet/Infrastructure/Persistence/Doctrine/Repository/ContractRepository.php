<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\CustomerInnRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractRepositoryInterface;

class ContractRepository implements ContractRepositoryInterface
{
    public function __construct(
        protected UserRepository $userRepo,
        protected CustomerInnRepository $customerInnRepo
    ) {}
    public function find(int $id): Contract
    {
        $user = $this->userRepo->find($id);

        return new Contract(
            $user->getId(),
            $user->getCustomerInn()->getInn(),
            $user->getFullName(),
            $user->getLogin(),
            (string)$user->getEmail(),
            (string)$user->getPhoneExtra(),
            (bool)$user->isDeleted(),
        );
    }


    public function findAllByInn(string $inn): array
    {
        $tableUsers = $this->userRepo->findBy(['customerInn' => $this->customerInnRepo->findby(['inn' => $inn])]);

        $contracts = [];
        foreach ($tableUsers as $tableUser) {
            $contracts[] = new Contract(
                $tableUser->getId(),
                $tableUser->getCustomerInn()->getInn(),
                $tableUser->getFullName(),
                $tableUser->getLogin(),
                (string)$tableUser->getEmail(),
                (string)$tableUser->getPhoneExtra(),
                (bool)$tableUser->isDeleted(),
            );
        }

        return $contracts;
    }

    public function archiveForReissue(Contract $contract): void
    {
        $user = $this->userRepo->find($contract->getId());
        $user->setLogin($user->getLogin() . '_old');
        $this->userRepo->save($user);
    }
}