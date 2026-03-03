<?php

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\AllHistoryKindRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllHistoryKindRepository::class)]
#[ORM\Table(name: 'all_history_kind')]
class AllHistoryKind
{
    #[ORM\Id]
    #[ORM\Column(name: 'hist_kind_id', type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'hist_kind_desc', type: Types::TEXT)]
    private string $description;

    #[ORM\Column(name: 'hist_kind_str_code', type: Types::STRING, length: 32, nullable: true, unique: true)]
    private ?string $strCode = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStrCode(): ?string
    {
        return $this->strCode;
    }

    public function setStrCode(?string $strCode): self
    {
        $this->strCode = $strCode;
        return $this;
    }
}
