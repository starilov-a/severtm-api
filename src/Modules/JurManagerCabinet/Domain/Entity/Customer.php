<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

use App\Modules\JurManagerCabinet\Domain\Entity\Contract\Contract;

class Customer
{

    protected ?string $password;

    protected array $contracts;

    public function __construct(
        protected int $inn,
        protected string $fio,
        protected string $login,
        protected string $email,
        protected string $phone,
    ) {}

    /**
     * @return array
     */
    public function getContracts(): array
    {
        return $this->contracts;
    }

    public function initContracts(array $contracts): void
    {
        $this->contracts = $contracts;
    }
    public function addContract(Contract $contract): void
    {
        $this->contracts[] = $contract;
    }
}