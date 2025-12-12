<?php

namespace App\Modules\Common\Domain\Entity;

use App\Modules\Common\Domain\Repository\UserPayableParameterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPayableParameterRepository::class)]
#[ORM\Table(name: 'upid_parameters')]
#[ORM\UniqueConstraint(name: 'uidx_up_upid_pc', columns: ['upid', 'param_code'])]
class UserPayableParameter
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: UserPayable::class)]
    #[ORM\JoinColumn(name: 'upid', referencedColumnName: 'upid', nullable: false, onDelete: 'CASCADE')]
    private UserPayable $userPayable;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: EnumParameter::class)]
    #[ORM\JoinColumn(name: 'param_code', referencedColumnName: 'param_code', nullable: false, onDelete: 'CASCADE')]
    private EnumParameter $parameter;

    #[ORM\Column(name: 'param_value', type: Types::STRING, length: 255, nullable: true)]
    private ?string $value = null;

    public function getUserPayable(): UserPayable
    {
        return $this->userPayable;
    }

    public function setUserPayable(UserPayable $userPayable): self
    {
        $this->userPayable = $userPayable;
        return $this;
    }

    public function getParameter(): EnumParameter
    {
        return $this->parameter;
    }

    public function setParameter(EnumParameter $parameter): self
    {
        $this->parameter = $parameter;
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

