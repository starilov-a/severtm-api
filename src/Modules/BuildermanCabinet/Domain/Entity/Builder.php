<?php

namespace App\Modules\BuildermanCabinet\Domain\Entity;

class Builder
{
    public function __construct(
        protected int $id,
        protected string $login,
        protected string $fio
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getFio(): string
    {
        return $this->fio;
    }

}