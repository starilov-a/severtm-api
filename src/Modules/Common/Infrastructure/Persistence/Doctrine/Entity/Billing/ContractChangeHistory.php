<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'master_uid', referencedColumnName: 'id', nullable: true)]
    private ?User $masterUser = null;

    #[ORM\Column(name: 'type_code', type: Types::STRING, length: 32, nullable: true)]
    private ?string $typeCode = null;

    #[ORM\Column(name: 'comments', type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    public function getId(): int { return $this->id; }
    public function setUser(?User $user): void { $this->user = $user; }
    public function setMasterUser(?User $masterUser): void { $this->masterUser = $masterUser; }
    public function setTypeCode(?string $typeCode): void { $this->typeCode = $typeCode; }
    public function setComments(?string $comments): void { $this->comments = $comments; }
}