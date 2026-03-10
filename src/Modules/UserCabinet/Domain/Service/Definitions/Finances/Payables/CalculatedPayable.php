<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Device;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableType;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;

class CalculatedPayable
{
    protected int $payable;
    protected int $amount;
    protected float $influence;

    protected User $user;
    protected UserServMode $servMode; // активная услугу
    protected UserPayableType $payableType; // тип платежа
    protected FinPeriod $currentFinPeriod;
    protected ?FinPeriod $refundFinPeriod = null;
    protected int $discount = 0; // скидка
    protected bool $isReal = true; // фиктивная или настоящая (?)
    protected bool $isApplied = true; // приминяется ли (?)
    protected int $prodCost;
    protected int $units;
    protected \DateTimeImmutable $createdAt;
    protected ?Device $device = null;

    public function getCurrentFinPeriod(): FinPeriod
    {
        return $this->currentFinPeriod;
    }

    public function setCurrentFinPeriod(FinPeriod $currentFinPeriod): void
    {
        $this->currentFinPeriod = $currentFinPeriod;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getProdCost(): int
    {
        return $this->prodCost;
    }

    public function setProdCost(int $prodCost): void
    {
        $this->prodCost = $prodCost;
    }

    public function getUnits(): int
    {
        return $this->units;
    }

    public function setUnits(int $units): void
    {
        $this->units = $units;
    }

    public function getPayable(): int
    {
        return $this->payable;
    }

    public function setPayable(int $payable): void
    {
        $this->payable = $payable;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getInfluence(): float
    {
        return $this->influence;
    }

    public function setInfluence(float $influence): void
    {
        $this->influence = $influence;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getServMode(): UserServMode
    {
        return $this->servMode;
    }

    public function setServMode(UserServMode $servMode): void
    {
        $this->servMode = $servMode;
    }

    public function getPayableType(): UserPayableType
    {
        return $this->payableType;
    }

    public function setPayableType(UserPayableType $payableType): void
    {
        $this->payableType = $payableType;
    }

    public function getRefundFinPeriod(): ?FinPeriod
    {
        return $this->refundFinPeriod;
    }

    public function setRefundFinPeriod(?FinPeriod $refundFinPeriod): void
    {
        $this->refundFinPeriod = $refundFinPeriod;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): void
    {
        $this->discount = $discount;
    }

    public function isReal(): bool
    {
        return $this->isReal;
    }

    public function setIsReal(bool $isReal): void
    {
        $this->isReal = $isReal;
    }

    public function isApplied(): bool
    {
        return $this->isApplied;
    }

    public function setIsApplied(bool $isApplied): void
    {
        $this->isApplied = $isApplied;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): void
    {
        $this->device = $device;
    }

}