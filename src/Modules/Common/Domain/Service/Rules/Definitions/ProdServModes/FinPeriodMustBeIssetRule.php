<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class FinPeriodMustBeIssetRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        //TODO стоит перенести в папку FinPeriod
        if (
            !($context instanceof HasUserId) ||
            !($context instanceof HasActionId) ||
            !($context instanceof HasNullbleVar)
        ) throw new \LogicException('Wrong context passed to FinPeriodMustBeIssetRule');

        // Основная бизнес логика
        if ($context->getNullbleVar() === null)
            return RuleResult::fail('Не указан финансовый период');

        return RuleResult::ok();
    }
}