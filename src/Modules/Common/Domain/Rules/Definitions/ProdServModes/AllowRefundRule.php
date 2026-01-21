<?php

namespace App\Modules\Common\Domain\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class AllowRefundRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasProdServMode))
            throw new \LogicException('Wrong context passed to AllowRefundRule');

        if (!$context->getMode()->getProdServModeCost()->isRefundAllowed())
            return RuleResult::fail("Перерасчет не доступен для режима {$context->getMode()->getName()}");

        return RuleResult::ok();
    }
}