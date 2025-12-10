<?php

namespace App\Modules\Common\Domain\Service\Rules\FinPeriod;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Rule;

class IsCurrentFinPeriodRule extends Rule
{

    public function check(object $context): bool
    {
        if (!$context instanceof HasFinPeriod)
            throw new \LogicException('Wrong context passed to IsCurrentFinPeriodRule');

        if (!$context->getFinPeriod()->isCurrent())
            return false;

        return true;
    }
}