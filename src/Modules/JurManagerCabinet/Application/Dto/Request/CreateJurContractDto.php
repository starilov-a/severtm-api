<?php

namespace App\Modules\JurManagerCabinet\Application\Dto\Request;

use App\Modules\Common\Application\Dto\Dto;
use App\Modules\JurManagerCabinet\Domain\Entity\Address;

class CreateJurContractDto extends Dto
{
    public function __construct(
        protected string $inn,
        protected string $fullName,
        protected string $login,
        protected string $password,
        protected string $passport,
        protected string $phone,
        protected string $extraPhone,
        protected string $email = '',
        protected Address $address,
        protected bool $isJuridical = true,
    ) {}

    public function getExtraPhone(): string
    {
        return $this->extraPhone;
    }

    public function setExtraPhone(string $extraPhone): void
    {
        $this->extraPhone = $extraPhone;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getPassport(): string
    {
        return $this->passport;
    }

    public function setPassport(string $passport): void
    {
        $this->passport = $passport;
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

    public function getTaxNumber(): string
    {
        return $this->inn;
    }

    public function getCurrentBankAccount(): string
    {
        return $this->phone;
    }

    public function getPhoneExtra(): string
    {
        return $this->phone;
    }

    public function getSurname(): string
    {
        return $this->splitFullName()[0] ?? '';
    }

    public function getName(): string
    {
        return $this->splitFullName()[1] ?? '';
    }

    public function getPatronymic(): string
    {
        return $this->splitFullName()[2] ?? '';
    }

    /**
     * @return list<string>
     */
    private function splitFullName(): array
    {
        $parts = preg_split('/\s+/u', trim($this->fullName)) ?: [];

        return array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));
    }
}
