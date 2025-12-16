<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTask;
use App\Modules\Common\Domain\Entity\UserTaskState;
use App\Modules\Common\Domain\Entity\UserTaskType;
use App\Modules\Common\Domain\Service\Dto\Dto;
use App\Modules\Common\Domain\Service\TaskService;

class CreateUserTaskDto extends Dto
{
    public function __construct(
        protected User $user,
        protected \DateTime $startDate,
        protected string $comment,
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

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = new \DateTime($startDate);
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
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