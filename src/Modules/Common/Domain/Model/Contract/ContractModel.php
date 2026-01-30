<?php

namespace App\Modules\Common\Domain\Model\Contract;

use App\Modules\Common\Domain\Entity\Address;

class ContractModel
{
    public function __construct(
        private int $id,
        private Address $address,
        private \DateTimeInterface $blockDate,
        private bool $isJuridical,
    ) {}

    public function getId(): int { return $this->id; }

    public function getAddress(): Address { return $this->address; }

    public function getBlockDate(): \DateTimeInterface { return $this->blockDate; }

    public function isJuridical(): bool { return $this->isJuridical; }

}