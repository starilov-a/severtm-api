<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\UserBalance;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

class BalanceMustBePositiveRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to IsNotIssetNewFreezeTaskRule');

        // Баланс + скидка
        $discount = !empty($context->getUser()->getDiscount()) ? $context->getUser()->getDiscount()->getQuantity() : 0;
        $availableAmount = $context->getUser()->getBill() + $discount;
        if ($availableAmount <= 0)
            return RuleResult::fail('Отрицательный баланс(с учетом скидок)');

        return RuleResult::ok();
    }
}