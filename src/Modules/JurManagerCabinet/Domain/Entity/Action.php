<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class Action
{
    protected function __construct(
        protected int $id,
        protected string $cid,
        protected string $name,
        protected string $description
    ) {}
}