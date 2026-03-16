<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasContract;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasReissueDate;

class ContractNotYetReissuedRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasContract))
            throw new \LogicException('Wrong context passed to ContractNotYetReissuedRule');

        if ($context->getContract()->isReissued())
            return RuleResult::fail('Договор уже переоформлен');

        return RuleResult::ok();
    }
}