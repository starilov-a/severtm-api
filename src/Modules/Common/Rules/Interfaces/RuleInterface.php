<?php

namespace App\Modules\Common\Rules\Interfaces;

use App\Modules\Common\Rules\Results\RuleResult;

interface RuleInterface
{
    public function check(object $context = null): RuleResult;
}