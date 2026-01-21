<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\CreditHistoryService;
use App\Modules\Common\Domain\Service\Dto\Dto;

class CreditHistoryLogDto extends Dto
{
    public function __construct(
        protected \DateTimeImmutable $creditDeadline,
        protected User $user,
        protected User $master,
        protected float $creditBill,
        protected \DateTimeImmutable $creditDate = new \DateTimeImmutable(),
        protected float $creditSum = 0.0,
    ) {}

    public function getCreditDate(): \DateTimeImmutable
    {
        return $this->creditDate;
    }

    public function setCreditDate(\DateTimeImmutable $creditDate): void
    {
        $this->creditDate = $creditDate;
    }

    public function getCreditDeadline(): \DateTimeImmutable
    {
        return $this->creditDeadline;
    }

    public function setCreditDeadline(\DateTimeImmutable $creditDeadline): void
    {
        $this->creditDeadline = $creditDeadline;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCreditSum(): float
    {
        return $this->creditSum;
    }

    public function setCreditSum(float $creditSum): void
    {
        $this->creditSum = $creditSum;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function setMaster(User $master): void
    {
        $this->master = $master;
    }

    public function getCreditBill(): float
    {
        return $this->creditBill;
    }

    public function setCreditBill(float $creditBill): void
    {
        $this->creditBill = $creditBill;
    }

}