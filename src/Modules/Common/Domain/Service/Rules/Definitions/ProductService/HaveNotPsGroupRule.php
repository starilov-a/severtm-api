<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProductService;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasProductService;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPsGroup;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

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