<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

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