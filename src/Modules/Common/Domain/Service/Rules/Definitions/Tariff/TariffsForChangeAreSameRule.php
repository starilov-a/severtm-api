<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasOldTariff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasTariff;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

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