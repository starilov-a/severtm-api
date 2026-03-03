<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\User;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\BlockStateRepositoryInterface;

class UserIsNotUnblockedRule  extends Rule
{
    public function __construct(
        protected BlockStateRepositoryInterface $blockStateRepo,
    ){}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        if ($context->getUser()->getBlockState() === $this->blockStateRepo->findByCode('unblocked'))
            return RuleResult::fail("Пользователь является не заблокированным");

        return RuleResult::ok();
    }
}