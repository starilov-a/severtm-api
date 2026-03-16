<?php

declare(strict_types=1);

namespace App\Modules\JurManagerCabinet\Domain\Entity\Task;

final class ScheduledTask
{
    public function __construct(
        private ?int $id,
        private string $type,
        private string $state,
        private int $contractId,
        private int $authorId,
        private \DateTimeImmutable $startDate,
        private array $payload = [],
        private int $attempts = 0,
    ) {
    }

    public function id(): ?int { return $this->id; }
    public function withId(int $id): self { $copy = clone $this; $copy->id = $id; return $copy; }
    public function type(): string { return $this->type; }
    public function contractId(): int { return $this->contractId; }
    public function authorId(): int { return $this->authorId; }
    public function dueAt(): \DateTimeImmutable { return $this->startDate; }
    public function state(): string { return $this->state; }
    public function payload(): array { return $this->payload; }
    public function attempts(): int { return $this->attempts; }

    public function markDone(): void { $this->state = TaskState::FINISHED; }
    public function scheduleRetry(\DateTimeImmutable $newDueAt): void
    {
        $this->attempts++;
        $this->startDate = $newDueAt;
        $this->state = TaskState::NEW;
    }

    public function markFailed(): void { $this->state = TaskState::ERROR; }
}
