<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity\Contract;

use App\Modules\JurManagerCabinet\Domain\Entity\Address;

class Contract
{
    public function __construct(
        protected int $id,
        protected string $inn,
        protected string $status,
        protected string $fullName,
        protected string $passport,
        protected string $login,
        protected string $email,
        protected string $phone,
        protected bool   $isReissued,
        protected Address $address,
    ) {}

    public function getPassport(): string
    {
        return $this->passport;
    }

    public function setPassport(string $passport): void
    {
        $this->passport = $passport;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function isReissued(): bool
    {
        return $this->isReissued;
    }
}