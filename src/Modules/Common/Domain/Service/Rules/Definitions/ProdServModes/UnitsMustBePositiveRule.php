<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasModeUnitCount;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class UnitsMustBePositiveRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (
            !($context instanceof HasUserId) ||
            !($context instanceof HasActionId) ||
            !($context instanceof HasModeUnitCount)
        ) throw new \LogicException('Wrong context passed to UnitsMustBePositiveRule');

        // Основная бизнес логика
        if ($context->getModeUnitCount() < 1)
            return RuleResult::fail('Указано некорректное кол-во услуг');

        return RuleResult::ok();
    }
}