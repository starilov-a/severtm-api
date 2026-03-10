<?php

namespace App\Modules\UserCabinet\Domain\Dto\Request;

use App\Modules\Common\Application\Dto\Dto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Device;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableType;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;

class CreateUserPayableDto extends Dto
{
    protected User $user;
    protected UserServMode $servMode; // активная услугу
    protected UserPayableType $payableType; // тип платежа
    protected ?FinPeriod $refundFinPeriod = null;
    protected int $discount = 0; // скидка
    protected bool $isReal = true; // фиктивная или настоящая (?)
    protected bool $isApplied = true; // приминяется ли (?)
    protected ?string $comment; // приминяется ли (?)
    protected ?Device $device = null;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    } // за какой месяц перерасчет или null

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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