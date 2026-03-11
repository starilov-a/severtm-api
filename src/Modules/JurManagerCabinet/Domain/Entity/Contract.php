<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class Contract
{
    public function __construct(
        protected int $id,
        protected string $inn,
        protected string $fullName,
        protected string $login,
        protected string $email,
        protected string $phone,
        protected bool $isArchived = false,
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

    public function isArchived(): bool
    {
        return $this->isArchived;
    }
}