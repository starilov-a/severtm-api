<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'z_ipaddr_parameters')]
class ZIpaddrParameter
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: ZIpaddr::class)]
    #[ORM\JoinColumn(name: 'ip', referencedColumnName: 'ip', nullable: false)]
    private ZIpaddr $ipAddress;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: EnumParameter::class)]
    #[ORM\JoinColumn(name: 'param_code', referencedColumnName: 'param_code', nullable: false)]
    private EnumParameter $parameter;

    #[ORM\Column(name: 'param_value', type: Types::STRING, length: 255, nullable: true)]
    private ?string $value = null;

    public function setIpAddress(ZIpaddr $ipAddress): void { $this->ipAddress = $ipAddress; }
    public function setParameter(EnumParameter $parameter): void { $this->parameter = $parameter; }
    public function setValue(?string $value): void { $this->value = $value; }
}