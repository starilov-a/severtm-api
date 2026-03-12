<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract;

use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract\OldInnAndNewInnAreDifferentRule;

class CanReissueContractRuleChain extends RuleChain
{
    public function __construct(
        OldInnAndNewInnAreDifferentRule $oldInnAndNewInnAreDifferentRule,
        CurrentOrNextFinPeriodRule $currentOrNextFinPeriodRule,
    )
    {
        $this->items = [
            new ChainRuleItem($oldInnAndNewInnAreDifferentRule),
            new ChainRuleItem($currentOrNextFinPeriodRule),
        ];
    }
}