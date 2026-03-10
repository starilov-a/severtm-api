<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class Contract
{
    public function __construct(
        protected int $id,
        protected string $inn
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getInn(): string
    {
        return $this->inn;
    }
}