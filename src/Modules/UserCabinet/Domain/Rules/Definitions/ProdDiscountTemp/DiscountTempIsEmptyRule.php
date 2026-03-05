<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\ProdDiscountTemp;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;

class DiscountTempIsEmptyRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to DiscountTempIsEmptyRule');

        $temps = $context->getUser()->getProdDiscountTemps();
        if (!$temps->isEmpty())
            return RuleResult::fail('Пользователь имеет активные задолженности');

        return RuleResult::ok();
    }
}