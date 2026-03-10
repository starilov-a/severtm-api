<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProdServModes;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProdServMode;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

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