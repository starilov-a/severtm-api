<?php

namespace App\Modules\UserCabinet\Domain\Dto\Request;

use App\Modules\Common\Application\Dto\Dto;
use App\Modules\UserCabinet\Domain\Entity\FreezeReason;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserTaskState;
use App\Modules\UserCabinet\Domain\Entity\UserTaskType;

class CreateUserTaskDto extends Dto
{
    public function __construct(
        protected User $user,
        protected \DateTimeImmutable $startDate,
        protected FreezeReason $reason,
        protected ?UserTaskType $userTaskType = null,
        protected ?UserTaskState $userTaskState = null,
    ){}

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getFreezeReason(): FreezeReason
    {
        return $this->reason;
    }

    public function setFreezeReason(FreezeReason $reason): void
    {
        $this->reason = $reason;
    }

    public function getUserTaskState(): ?UserTaskState
    {
        return $this->userTaskState;
    }

    public function setUserTaskState(UserTaskState $userTaskState): void
    {
        $this->userTaskState = $userTaskState;
    }

    public function getUserTaskType(): ?UserTaskType
    {
        return $this->userTaskType;
    }

    public function setUserTaskType(UserTaskType $userTaskType): void
    {
        $this->userTaskType = $userTaskType;
    }
}