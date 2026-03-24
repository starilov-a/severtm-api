<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Chains\Contract;

use App\Modules\Common\Domain\Rules\RuleChain;

class CanCreateJurContractRuleChain extends RuleChain
{
    public function __construct()
    {
        $this->items = [];
    }
}