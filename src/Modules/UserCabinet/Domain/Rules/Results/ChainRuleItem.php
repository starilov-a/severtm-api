<?php

namespace App\Modules\UserCabinet\Domain\Rules\Results;

use App\Modules\UserCabinet\Domain\Rules\Interfaces\RuleInterface;

final class ChainRuleItem
{
    public function __construct(
        public readonly RuleInterface $rule,
        public readonly RuleMode $mode = RuleMode::HARD,
        public readonly ?string $exceptionClass = null,
    ) {}
}