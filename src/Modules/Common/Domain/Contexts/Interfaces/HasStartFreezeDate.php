<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

interface HasStartFreezeDate
{
    public function getStartFreezeDate(): \DateTimeImmutable;
}