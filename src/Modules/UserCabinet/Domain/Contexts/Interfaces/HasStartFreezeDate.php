<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasStartFreezeDate
{
    public function getStartFreezeDate(): \DateTimeImmutable;
}