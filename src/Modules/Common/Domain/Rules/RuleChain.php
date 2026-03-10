<?php

namespace App\Modules\Common\Domain\Rules;

use App\Modules\Common\Domain\Rules\Interfaces\RuleChainInterface;
use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\Results\RuleResult;

class RuleChain implements RuleChainInterface
{
    /** @var ChainRuleItem[] */
    protected array $items = [];

    public function checkAll(object $context): RuleResult
    {
        foreach ($this->items as $item) {
            $result = $item->rule->check($context);

            if ($result->ok)
                continue;

            return $result;
        }
        return RuleResult::ok();
    }


    public function checkAny(object $context): RuleResult
    {
        // В этом методе НЕ бросаем исключения (по названию WithResult).
        // Просто возвращаем ok или причину.
        $firstFail = null; /** @var RuleResult|null $firstFail */

        foreach ($this->items as $item) {
            $result = $item->rule->check($context);

            if ($result->ok)
                return RuleResult::ok();

            $firstFail ??= $result;
        }

        return $firstFail ?? RuleResult::fail('No rules configured'); // если items пустой
    }
}