<?php

namespace App\Modules\UserCabinet\Domain\Rules\Interfaces;

use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;

interface RuleInterface
{
    public function check(object $context = null): RuleResult;
}