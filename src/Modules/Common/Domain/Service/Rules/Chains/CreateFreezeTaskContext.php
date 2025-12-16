<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTaskState;
use App\Modules\Common\Domain\Entity\UserTaskType;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;

class CreateFreezeTaskContext implements HasUser, HasMaster, HasActionId
{
    public function __construct(
        protected User $user,
        protected User $master,
        protected int $actionId,
        protected \DateTimeInterface $startDate,
        protected UserTaskType $freezeTaskType,
        protected UserTaskState $newState,
        protected UserTaskState $finishedState,
        protected FinPeriod $currentFinPeriod,
        protected float $defaultTariffCost,
        protected float $userBill,
        protected ?\DateTimeImmutable $registrationDate,
        protected \DateTimeImmutable $now,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserId(): int
    {
        return $this->user->getId();
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function getFreezeTaskType(): UserTaskType
    {
        return $this->freezeTaskType;
    }

    public function getNewState(): UserTaskState
    {
        return $this->newState;
    }

    public function getFinishedState(): UserTaskState
    {
        return $this->finishedState;
    }

    public function getCurrentFinPeriod(): FinPeriod
    {
        return $this->currentFinPeriod;
    }

    public function getDefaultTariffCost(): float
    {
        return $this->defaultTariffCost;
    }

    public function getUserBill(): float
    {
        return $this->userBill;
    }

    public function getRegistrationDate(): ?\DateTimeImmutable
    {
        return $this->registrationDate;
    }

    public function getNow(): \DateTimeImmutable
    {
        return $this->now;
    }
}
