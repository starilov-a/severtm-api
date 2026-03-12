<?php

namespace App\Modules\JurManagerCabinet\Application\Dto\Request;

use App\Modules\Common\Application\Dto\Dto;

class ReissueContractDto extends Dto
{
    public function __construct(
        protected int $contractId,
        protected int $managerId,
        protected int $newInn,
        protected string $fio,
        protected string $login,
        protected string $password,
        protected string $phone
    ) {}

    public function getContractId(): int
    {
        return $this->contractId;
    }

    public function setContractId(int $contractId): void
    {
        $this->contractId = $contractId;
    }

    public function getManagerId(): int
    {
        return $this->managerId;
    }

    public function setManagerId(int $managerId): void
    {
        $this->managerId = $managerId;
    }

    public function getNewInn(): int
    {
        return $this->newInn;
    }

    public function setNewInn(int $newInn): void
    {
        $this->newInn = $newInn;
    }

    public function getFio(): string
    {
        return $this->fio;
    }

    public function setFio(string $fio): void
    {
        $this->fio = $fio;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

}