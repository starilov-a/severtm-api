<?php

namespace App\Modules\JurManagerCabinet\Application\Dto\Request\Queue;

use App\Modules\JurManagerCabinet\Application\Dto\Request\Reissue\ReissueContractDto;

class ScheduleReissueTaskDto
{
    public function __construct(
        protected string $taskType,
        protected string $taskState,
        protected string $contractId,
        protected string $newContractId,
        protected string $managerId,
        protected \DateTimeImmutable $createAt,
        protected \DateTimeImmutable $startAt,
        protected string $comment,
        protected ReissueContractDto $contractReissueProcess
    ) {}

    public function getNewContractId(): string
    {
        return $this->newContractId;
    }

    public function getTaskType(): string
    {
        return $this->taskType;
    }

    public function setTaskType(string $taskType): void
    {
        $this->taskType = $taskType;
    }

    public function getTaskState(): string
    {
        return $this->taskState;
    }

    public function setTaskState(string $taskState): void
    {
        $this->taskState = $taskState;
    }

    public function getContractId(): string
    {
        return $this->contractId;
    }

    public function setContractId(string $contractId): void
    {
        $this->contractId = $contractId;
    }

    public function getManagerId(): string
    {
        return $this->managerId;
    }

    public function setManagerId(string $managerId): void
    {
        $this->managerId = $managerId;
    }

    public function getCreateAt(): \DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): void
    {
        $this->createAt = $createAt;
    }

    public function getStartAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getContractReissueProcess(): ReissueContractDto
    {
        return $this->contractReissueProcess;
    }

    public function setContractReissueProcess(ReissueContractDto $contractReissueProcess): void
    {
        $this->contractReissueProcess = $contractReissueProcess;
    }
}