<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class IssetContractRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasNewInn))
            throw new \LogicException('Wrong context passed to OldInnAndNewInnAreDifferent');

        $bool = $repo->issetContract();

        if (!$bool)
            return RuleResult::fail('Такой договор не существует');

        return RuleResult::ok();
    }
}