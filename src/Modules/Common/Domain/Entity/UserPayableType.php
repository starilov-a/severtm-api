<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\UserPayableTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPayableTypeRepository::class)]
#[ORM\Table(name: 'user_payables_types')]
#[ORM\UniqueConstraint(name: 'uidx_upt_type_code', columns: ['type_code'])]
class UserPayableType
{
    #[ORM\Id]
    #[ORM\Column(name: 'type_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'type_code', type: Types::STRING, length: 16, options: ['default' => ''])]
    private string $code = '';

    #[ORM\Column(name: 'description', type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): int
    {
        return $this->id;
    }

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

