<?php

namespace App\Modules\UserCabinet\Domain\Entity;

use App\Modules\UserCabinet\Domain\Repository\ConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
#[ORM\Table(name: 'config')]
class Config
{
    #[ORM\Id]
    #[ORM\Column(name: 'cid', type: Types::STRING, length: 64, nullable: false)]
    private string $cid;

    #[ORM\Column(name: 'value', type: Types::STRING, length: 128, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getCid(): string
    {
        return $this->cid;
    }

    public function setCid(string $cid): self
    {
        $this->cid = $cid;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}

