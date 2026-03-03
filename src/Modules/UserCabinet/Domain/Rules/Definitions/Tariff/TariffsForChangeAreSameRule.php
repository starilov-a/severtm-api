<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasOldTariff;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasTariff;

class TariffsForChangeAreSameRule extends Rule
{
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasTariff) || !($context instanceof HasOldTariff))
            throw new \LogicException('Wrong context passed to TariffsForChangeAreSameRule');

        if ($context->getTariff() === $context->getOldTariff())
            return RuleResult::fail('Старый и новый тариф являются одинаковыми');

        return RuleResult::ok();
    }
}