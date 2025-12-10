<?php

namespace App\Modules\Common\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'devices')]
#[ORM\UniqueConstraint(name: 'uidx_dev_sn_dt', columns: ['serial_num', 'device_type_id'])]
class Device
{
    #[ORM\Id]
    #[ORM\Column(name: 'device_id', type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'serial_num', type: Types::STRING, length: 255)]
    private string $serialNumber;

    #[ORM\Column(name: 'device_type_id', type: Types::INTEGER, nullable: true)]
    private ?int $deviceTypeId = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    public function getDeviceTypeId(): ?int
    {
        return $this->deviceTypeId;
    }

    public function setDeviceTypeId(?int $deviceTypeId): self
    {
        $this->deviceTypeId = $deviceTypeId;
        return $this;
    }
}
