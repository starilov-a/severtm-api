<?php

namespace App\Modules\Common\Domain\Service\Rules\Interfaces;

use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;

interface RuleInterface
{
    public function check(object $context = null): RuleResult;
}