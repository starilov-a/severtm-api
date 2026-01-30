<?php

namespace App\Modules\Common\Domain\Model\Customer;

abstract class CustomerModel
{
    protected CustomerKey $key;
    protected string $login;

    public function getKey(): CustomerKey
    {
        return $this->key;
    }

    public function setKey(CustomerKey $key): void
    {
        $this->key = $key;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }



}