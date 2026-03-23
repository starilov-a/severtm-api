<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'contract_change_history')]
class ContractChangeHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'history_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'time_stamp', type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $timeStamp;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'master_uid', referencedColumnName: 'id', nullable: true)]
    private ?User $masterUser = null;

    #[ORM\ManyToOne(targetEntity: ContractChangeType::class, inversedBy: 'histories')]
    #[ORM\JoinColumn(name: 'type_code', referencedColumnName: 'code_str', nullable: true)]
    private ?ContractChangeType $type = null;

    #[ORM\Column(name: 'comments', type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    /**
     * @var Collection<int, ContractChangeHistoryParam>
     */
    #[ORM\OneToMany(
        mappedBy: 'history',
        targetEntity: ContractChangeHistoryParam::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $params;

    public function __construct()
    {
        $this->timeStamp = new \DateTimeImmutable();
        $this->params = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $masterUser): void
    {
        $this->masterUser = $masterUser;
    }

    public function getType(): ?ContractChangeType
    {
        return $this->type;
    }

    public function setType(?ContractChangeType $type): void
    {
        $this->type = $type;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return ContractChangeHistoryParam[]
     */
    public function getParams(): array
    {
        return $this->params->toArray();
    }

    public function addParam(ContractChangeHistoryParam $param): void
    {
        if (!$this->params->contains($param)) {
            $this->params->add($param);
            $param->setHistory($this);
        }
    }

    public function removeParam(ContractChangeHistoryParam $param): void
    {
        if ($this->params->removeElement($param)) {
            $param->setHistory($this);
        }
    }
}
