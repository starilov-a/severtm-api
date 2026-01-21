<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

interface HasFinPeriodId
{
    public function getFinPeriodId(): int;
}