<?php

namespace App\Modules\Starts\Domain\Entity;

class Application
{
    public function __construct(
        protected int $id,
        protected string $addressName,
        protected Builder $builder,
        protected ApplicationStatus $applicationStatus,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddressName(): string
    {
        return $this->addressName;
    }

    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function getApplicationStatus(): ApplicationStatus
    {
        return $this->applicationStatus;
    }
}