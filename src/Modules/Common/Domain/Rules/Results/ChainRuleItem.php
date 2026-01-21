<?php

namespace App\Modules\Common\Domain\Rules\Results;

use App\Modules\Common\Domain\Rules\Interfaces\RuleInterface;

final class ChainRuleItem
{
    public function __construct(
        public readonly RuleInterface $rule,
        public readonly RuleMode $mode = RuleMode::HARD,
        public readonly ?string $exceptionClass = null,
    ) {}
}