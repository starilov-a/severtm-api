<?php

namespace App\Modules\Common\Domain\Rules\Interfaces;

use App\Modules\Common\Domain\Rules\Results\RuleResult;

interface RuleInterface
{
    public function check(object $context = null): RuleResult;
}