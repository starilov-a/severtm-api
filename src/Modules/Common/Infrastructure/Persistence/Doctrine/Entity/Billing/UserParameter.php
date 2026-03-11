<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_parameters')]
class UserParameter
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'uid', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: EnumParameter::class)]
    #[ORM\JoinColumn(name: 'params_type', referencedColumnName: 'param_code', nullable: false)]
    private EnumParameter $parameter;

    #[ORM\Column(name: 'params_value', type: Types::STRING, length: 255)]
    private string $value;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getParameter(): EnumParameter
    {
        return $this->parameter;
    }

    public function setParameter(EnumParameter $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}