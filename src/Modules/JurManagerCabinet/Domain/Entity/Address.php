<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class Address
{
    public function __construct(
        readonly protected int $id,
        readonly protected string $name,
        readonly protected int $regionId,
        readonly protected string $regionName
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRegionId(): int
    {
        return $this->regionId;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

}