<?php

namespace App\Modules\UserCabinet\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'enum_parameters')]
class EnumParameter
{
    #[ORM\Id]
    #[ORM\Column(name: 'param_code', type: Types::STRING, length: 32)]
    private string $code;

    #[ORM\Column(name: 'param_desc', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
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

