<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\CustomerInnRepository;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Contract;
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

        $contract = new Contract(
            $user->getId(),
            $user->getCustomerInn()->getInn(),
        );

        return $contract;
    }


    public function findAllByInn(string $inn): array
    {
        $tableUsers = $this->userRepo->findBy(['customerInn' => $this->customerInnRepo->findby(['inn' => $inn])]);

        $contracts = [];
        foreach ($tableUsers as $tableUser) {
            $contract = new Contract(
                $tableUser->getId(),
                $tableUser->getCustomerInn()->getInn(),
            );

            array_push($contracts, $contract);
        }

        return $contracts;
    }
}