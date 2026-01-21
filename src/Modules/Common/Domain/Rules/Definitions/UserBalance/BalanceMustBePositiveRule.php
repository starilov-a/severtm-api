<?php

namespace App\Modules\Common\Domain\Rules\Definitions\UserBalance;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class BalanceMustBePositiveRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to IsNotIssetNewFreezeTaskRule');

        // Баланс + скидка
        $availableAmount = $context->getUser()->getBill() + $context->getUser()->getDiscount()->getQuantity();
        if ($availableAmount <= 0)
            return RuleResult::fail('Отрицательный баланс(с учетом скидок)');

        return RuleResult::ok();
    }
}