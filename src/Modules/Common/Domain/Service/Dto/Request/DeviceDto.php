<?php

namespace App\Modules\Common\Domain\Service\Dto\Request;

class DeviceDto
{
    public function __construct(
        protected int $id,
        protected string $serialNumber,
        protected ?int $deviceTypeId = null,
        protected ?string $comment = null,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function getDeviceTypeId(): ?int
    {
        return $this->deviceTypeId;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}

