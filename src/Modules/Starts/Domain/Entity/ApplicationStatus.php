<?php

namespace App\Modules\Starts\Domain\Entity;

class ApplicationStatus
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected string $strCode,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStrCode(): string
    {
        return $this->strCode;
    }
}