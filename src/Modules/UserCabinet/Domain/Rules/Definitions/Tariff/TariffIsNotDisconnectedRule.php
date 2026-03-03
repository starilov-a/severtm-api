<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;

class TariffIsNotDisconnectedRule extends Rule
{
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasTariff))
            throw new \LogicException('Wrong context passed to TariffIsNotDisconnectedRule');

        if ($context->getTariff()->isDisconnected())
            return RuleResult::fail('Тариф является "Отключен от сети"');

        return RuleResult::ok();
    }
}