<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;

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