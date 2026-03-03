<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;

class CanBeChangedByClientRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasTariff))
            throw new \LogicException('Wrong context passed to IssetRentProdModeNowForDisconnectRule');

        if ($context->getTariff()->canBeChangedByClient())
            return RuleResult::fail('Тариф не доступен для смены клиентом');

        return RuleResult::ok();
    }
}