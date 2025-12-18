<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

interface HasFinPeriodId
{
    public function getFinPeriodId(): int;
}