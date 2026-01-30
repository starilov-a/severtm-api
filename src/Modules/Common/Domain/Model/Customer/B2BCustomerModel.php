<?php

namespace App\Modules\Common\Domain\Model\Customer;

class B2BCustomerModel extends CustomerModel
{
    protected int $inn;

    public function getInn(): int
    {
        return $this->inn;
    }

    public function setInn(int $inn): void
    {
        $this->inn = $inn;
    }
}