<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract;

use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract\OldInnAndNewInnAreDifferentRule;

class CanReregestrationContractRuleChain extends RuleChain
{
    public function __construct(
        OldInnAndNewInnAreDifferentRule $oldInnAndNewInnAreDifferentRule,
    )
    {
        $this->items = [
            new ChainRuleItem($oldInnAndNewInnAreDifferentRule),
        ];
    }
}