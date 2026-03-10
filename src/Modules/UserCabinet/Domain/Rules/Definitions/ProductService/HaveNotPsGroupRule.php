<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProductService;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasProductService;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasPsGroup;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

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