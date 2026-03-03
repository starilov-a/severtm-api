<?php

namespace App\Modules\Common\Rules\Results;

use App\Modules\Common\Rules\Interfaces\RuleInterface;

final class ChainRuleItem
{
    public function __construct(
        public readonly RuleInterface $rule,
        public readonly RuleMode $mode = RuleMode::HARD,
        public readonly ?string $exceptionClass = null,
    ) {}
}