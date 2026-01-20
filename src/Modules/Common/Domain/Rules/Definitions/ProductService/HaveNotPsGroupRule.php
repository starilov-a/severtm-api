<?php

namespace App\Modules\Common\Domain\Rules\Definitions\ProductService;

use App\Modules\Common\Domain\Contexts\Interfaces\HasProductService;
use App\Modules\Common\Domain\Contexts\Interfaces\HasPsGroup;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class HaveNotPsGroupRule extends Rule
{
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasPsGroup) || !($context instanceof HasProductService))
            throw new \LogicException('Wrong context passed to HaveNotPsGroupRule');

        if ($context->getProductService()->hasGroup($context->getPsGroup()))
            RuleResult::fail("Услуга {$context->getProductService()->getName()} принадлежит к группе: {$context->getPsGroup()->getName()}");

        return RuleResult::ok();
    }
}