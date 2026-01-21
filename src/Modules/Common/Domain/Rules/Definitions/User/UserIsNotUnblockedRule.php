<?php

namespace App\Modules\Common\Domain\Rules\Definitions\User;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class UserIsNotUnblockedRule  extends Rule
{
    public function __construct(
        protected BlockStateRepository $blockStateRepo,
    ){}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        if ($context->getUser()->getBlockState() === $this->blockStateRepo->findByCode('unblocked'))
            return RuleResult::fail("Пользователь является не заблокированным");

        return RuleResult::ok();
    }
}