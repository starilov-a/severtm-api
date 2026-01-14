<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

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