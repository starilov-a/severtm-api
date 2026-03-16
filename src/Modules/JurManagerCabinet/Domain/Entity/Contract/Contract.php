<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity\Contract;

class Contract
{
    public function __construct(
        protected int $id,
        protected string $inn,
        protected ContractStatus $status,
        protected string $fullName,
        protected string $login,
        protected string $email,
        protected string $phone,
        protected bool   $isReissued
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

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