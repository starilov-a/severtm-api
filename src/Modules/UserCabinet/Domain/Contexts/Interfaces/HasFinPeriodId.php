<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasFinPeriodId
{
    public function getFinPeriodId(): int;
}