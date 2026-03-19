<?php declare(strict_types=1);

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_task_parameters')]
class UserTaskParameter
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: UserTask::class)]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: false)]
    private UserTask $task;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: EnumTask::class)]
    #[ORM\JoinColumn(name: 'param_code', referencedColumnName: 'param_code', nullable: false)]
    private EnumTask $type;

    #[ORM\Column(name: 'param_value', type: Types::STRING, length: 255, nullable: true)]
    private ?string $value = null;

    public function getTask(): UserTask
    {
        return $this->task;
    }

    public function setTask(UserTask $task): self
    {
        $this->task = $task;
        return $this;
    }

    public function getType(): EnumTask
    {
        return $this->type;
    }

    public function setType(EnumTask $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
