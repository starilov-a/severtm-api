<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class ContractReissueSettings
{
    public function __construct(
        protected array $parameters = [],
        protected array $webIps = [],
        protected array $networkIps = [],
        protected array $groupIds = [],
        protected array $serviceModes = [],
        protected array $devices = [],
        protected array $phones = [],
        protected ?float $discount = null,
        protected ?int $tariffId = null,
    ) {}

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getWebIps(): array
    {
        return $this->webIps;
    }

    public function getNetworkIps(): array
    {
        return $this->networkIps;
    }

    public function getGroupIds(): array
    {
        return $this->groupIds;
    }

    public function getServiceModes(): array
    {
        return $this->serviceModes;
    }

    public function getDevices(): array
    {
        return $this->devices;
    }

    public function getPhones(): array
    {
        return $this->phones;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function getTariffId(): ?int
    {
        return $this->tariffId;
    }
}
