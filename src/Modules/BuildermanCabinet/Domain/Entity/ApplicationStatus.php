<?php

namespace App\Modules\BuildermanCabinet\Domain\Entity;

class ApplicationStatus
{
    public function __construct(
        protected string $name,
        protected string $strCode,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getStrCode(): string
    {
        return $this->strCode;
    }
}