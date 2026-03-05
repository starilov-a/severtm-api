<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class Contract
{
    public function __construct(
        protected int $id,
        protected string $inn
    ) {}
}