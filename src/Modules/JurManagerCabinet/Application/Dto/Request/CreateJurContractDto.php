<?php

namespace App\Modules\JurManagerCabinet\Application\Dto\Request;

use App\Modules\Common\Application\Dto\Dto;

class CreateJurContractDto extends Dto
{
    public function __construct(
        protected string $login,
        protected string $password,
        protected string $inn,
        protected string $fullName,
        protected string $password,
        protected string $phone,
        protected bool $isJuridical = true,
    ) {}

    public function getInn(): string
    {
        return $this->inn;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function isJuridical(): bool
    {
        return $this->isJuridical;
    }
}