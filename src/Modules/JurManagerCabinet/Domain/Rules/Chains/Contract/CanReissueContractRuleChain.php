<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract;

use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract\ContractNotYetReissuedRule;
use App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract\CurrentOrNextFinPeriodRule;
use App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract\OldInnAndNewInnAreDifferentRule;
use App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract\ReissueNotYetScheduledRule;

class CanReissueContractRuleChain extends RuleChain
{
    public function __construct(
        OldInnAndNewInnAreDifferentRule $oldInnAndNewInnAreDifferentRule,
        CurrentOrNextFinPeriodRule      $currentOrNextFinPeriodRule,
        ReissueNotYetScheduledRule      $reissueNotYetScheduledRule,
        ContractNotYetReissuedRule      $contractNotYetReissued,
    )
    {
        $this->items = [
            new ChainRuleItem($oldInnAndNewInnAreDifferentRule),
            new ChainRuleItem($currentOrNextFinPeriodRule),
            new ChainRuleItem($reissueNotYetScheduledRule),
            new ChainRuleItem($contractNotYetReissued)
        ];
    }
}