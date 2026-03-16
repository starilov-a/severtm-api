<?php

declare(strict_types=1);

namespace App\Modules\JurManagerCabinet\Domain\Entity\Reissue;

use App\Modules\JurManagerCabinet\Application\Dto\Request\Reissue\ReissueContractDto;

/**
 * Информация о процессе переоформления. Хранится вся информация, необходимая для переоформления.
 */
class ContractReissueProcess
{
    public function __construct(
        private int $oldContractId,
        private int $newContractId,
        private ReissueContractDto $contractReissueDto,
        private \DateTimeImmutable $scheduleAt,
        private string $status = ContractReissueStatus::SCHEDULED,
        private ?int $taskId = null,
        private ?\DateTimeImmutable $startedAt = null,
        private ?\DateTimeImmutable $completedAt = null,
    ) {}

    public function getScheduleAt(): \DateTimeImmutable
    {
        return $this->scheduleAt;
    }

    public function setScheduleAt(\DateTimeImmutable $scheduleAt): void
    {
        $this->scheduleAt = $scheduleAt;
    }

    public function getContractReissueDto(): ReissueContractDto
    {
        return $this->contractReissueDto;
    }

    public function setContractReissueDto(ReissueContractDto $contractReissueDto): void
    {
        $this->contractReissueDto = $contractReissueDto;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): void
    {
        $this->startedAt = $startedAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    public function getNewContractId(): int
    {
        return $this->newContractId;
    }

    public function setNewContractId(int $newContractId): void
    {
        $this->newContractId = $newContractId;
    }

    public function getOldContractId(): int
    {
        return $this->oldContractId;
    }

    public function setOldContractId(int $oldContractId): void
    {
        $this->oldContractId = $oldContractId;
    }

    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function setTaskId(int $taskId): void
    {
        $this->taskId = $taskId;
    }

}
