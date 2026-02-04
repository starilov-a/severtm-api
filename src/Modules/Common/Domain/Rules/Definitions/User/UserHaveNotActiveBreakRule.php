<?php

namespace App\Modules\Common\Domain\Rules\Definitions\User;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class UserHaveNotActiveBreakRule extends Rule
{
    public function __construct(
    ){}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to UserHaveNotActiveBreakRule');

        if ($context->getUser()->isCredit())
            return RuleResult::fail("Пользователь имеет активную отсрочку");

        return RuleResult::ok();
    }
}