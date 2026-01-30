<?php

namespace App\Modules\Common\Domain\Aggregates;

use App\Modules\Common\Domain\Model\Customer\CustomerModel;

class CustomerProfileAggregate
{
    /**
     * @param int[] $contracts
     */
    public function __construct(
        protected readonly CustomerModel $customer,
        protected readonly array $contracts,
        protected readonly int $totalContracts,
    ) {}

    public function getCustomer(): CustomerModel
    {
        return $this->customer;
    }

    public function getContracts(): array
    {
        return $this->contracts;
    }

    public function getTotalContracts(): int
    {
        return $this->totalContracts;
    }

}