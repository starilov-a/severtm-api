<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'all_history')]
class AllHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'history_id', type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(name: 'time_stamp', type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $timeStamp;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(name: 'tag', type: Types::STRING, length: 1)]
    private string $tag;

    #[ORM\ManyToOne(targetEntity: AllHistoryKind::class)]
    #[ORM\JoinColumn(name: 'hist_kind_id', referencedColumnName: 'hist_kind_id', nullable: false)]
    private AllHistoryKind $kind;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'master_uid', referencedColumnName: 'id', nullable: false)]
    private User $master;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTimeStamp(): \DateTimeInterface
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(\DateTimeInterface $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    public function getKind(): AllHistoryKind
    {
        return $this->kind;
    }

    public function setKind(AllHistoryKind $kind): void
    {
        $this->kind = $kind;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function setMaster(User $master): void
    {
        $this->master = $master;
    }
}
