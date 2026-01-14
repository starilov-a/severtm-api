<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\ProdDiscountTemp;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class DiscountTempIsEmptyRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to DiscountTempIsEmptyRule');

        $temps = $context->getUser()->getProdDiscountTemps();
        if ($temps->isEmpty())
            return RuleResult::fail('Пользователь имеет активные задолженности');

        return RuleResult::ok();
    }
}