<?php

namespace App\Modules\Common\Domain\Service\Rules;

class RuleChain implements RuleChainInterface
{
    /** @param RuleInterface[] $rules */
    protected array $rules;
    public function checkAll(object $context): bool
    {
        foreach ($this->rules as $rule) {
            if (!$rule->check($context))
                return false;
        }
        return true;
    }
}