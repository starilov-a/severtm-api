<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\UserTaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTaskRepository::class)]
#[ORM\Table(name: 'user_tasks')]
#[ORM\Index(name: 'users__id', columns: ['uid'])]
#[ORM\Index(name: 'wu__uid', columns: ['who'])]
#[ORM\Index(name: 'fk_user_tasks_ref_user_task_types', columns: ['type'])]
#[ORM\Index(name: 'fk_user_tasks_ref_user_task_states', columns: ['state'])]
class UserTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: UserTaskType::class)]
    #[ORM\JoinColumn(name: 'type', referencedColumnName: 'task_type_id', nullable: false)]
    private UserTaskType $type;

    #[ORM\ManyToOne(targetEntity: UserTaskState::class)]
    #[ORM\JoinColumn(name: 'state', referencedColumnName: 'task_state_id', nullable: false)]
    private UserTaskState $state;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    /** Сотрудник, который поставил/изменил задачу (web_users.uid). */
    #[ORM\ManyToOne(targetEntity: WebUser::class)]
    #[ORM\JoinColumn(name: 'who', referencedColumnName: 'uid', nullable: false)]
    private WebUser $author;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(name: 'start_time', type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => '0000-00-00 00:00:00'])]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(name: 'comment', type: Types::STRING, length: 100, nullable: true)]
    private ?string $comment = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): UserTaskType
    {
        return $this->type;
    }

    public function setType(UserTaskType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getState(): UserTaskState
    {
        return $this->state;
    }

    public function setState(UserTaskState $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAuthor(): WebUser
    {
        return $this->author;
    }

    public function setAuthor(WebUser $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
