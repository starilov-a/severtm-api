<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\SemaphoreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemaphoreRepository::class)]
#[ORM\Table(name: '__semaphores')]
#[ORM\UniqueConstraint(name: 'uidx_semaphores_procedure_name', columns: ['procedure_name'])]
class Semaphore
{
    #[ORM\Id]
    #[ORM\Column(name: 'procedure_name', type: Types::STRING, length: 64)]
    private string $procedureName;

    #[ORM\Column(name: 'is_running', type: Types::INTEGER, options: ['default' => 0])]
    private int $isRunning = 0;

    #[ORM\Column(name: 'pid', type: Types::BIGINT, options: ['default' => 0])]
    private int $pid = 0;

    #[ORM\Column(name: 'start_time', type: Types::INTEGER, options: ['default' => 0])]
    private int $startTime = 0;

    #[ORM\Column(name: 'end_time', type: Types::INTEGER, options: ['default' => 0])]
    private int $endTime = 0;

    public function getProcedureName(): string
    {
        return $this->procedureName;
    }

    public function setProcedureName(string $procedureName): self
    {
        $this->procedureName = $procedureName;

        return $this;
    }

    public function isRunning(): bool
    {
        return $this->isRunning === 1;
    }

    public function setIsRunning(bool $isRunning): self
    {
        $this->isRunning = $isRunning ? 1 : 0;

        return $this;
    }

    public function getPid(): int
    {
        return $this->pid;
    }

    public function setPid(int $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function setStartTime(int $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): int
    {
        return $this->endTime;
    }

    public function setEndTime(int $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }
}

