<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasNewInn;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasOldInn;

class OldInnAndNewInnAreDifferentRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasNewInn) || !($context instanceof HasOldInn))
            throw new \LogicException('Wrong context passed to OldInnAndNewInnAreDifferent');

        if ($context->getNewInn() === $context->getOldInn())
            return RuleResult::fail('Старый и новый ИНН одинаковы');

        return RuleResult::ok();
    }
}