<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasModeUnitCount;

class UnitsMustBePositiveRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasModeUnitCount)) throw new \LogicException('Wrong context passed to UnitsMustBePositiveRule');

        // Основная бизнес логика
        if ($context->getModeUnitCount() < 1)
            return RuleResult::fail('Указано некорректное кол-во услуг');

        return RuleResult::ok();
    }
}