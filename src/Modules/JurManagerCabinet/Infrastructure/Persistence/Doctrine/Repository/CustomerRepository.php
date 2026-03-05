<?php

namespace App\Modules\JurManagerCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing\UserRepository;
use App\Modules\JurManagerCabinet\Domain\Entity\Customer;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\CustomerRepositoryInterface;


class CustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        protected UserRepository        $userRepo,
        protected ContractRepository    $contractRepo,
    ) {}

    public function find(int $id): Customer
    {
        $tableUser = $this->userRepo->find($id);
        $customer = new Customer(
            $tableUser->getCustomerInn()->getInn(),
            $tableUser->getFullName(),
            $tableUser->getLogin(),
            $tableUser->getEmail(),
            $tableUser->getPhoneExtra(), // TODO: Тот ли это номер
        );

        $customer->initContracts($this->contractRepo->findAllByInn($tableUser->getCustomerInn()->getInn()));

        return $customer;
    }

}