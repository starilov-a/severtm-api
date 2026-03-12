<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity;

class ReissueProcess
{
    public function __construct(
        protected int $contractId,
        protected int $managerId,
        protected int $newInn,
        protected string $fio,
        protected string $login,
        protected string $password,
        protected string $phone
    ) {}
}