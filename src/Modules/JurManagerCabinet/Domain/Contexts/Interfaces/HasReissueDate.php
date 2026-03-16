<?php

namespace App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces;

interface HasReissueDate
{
    public function getReissueDate(): \DateTimeImmutable;
}