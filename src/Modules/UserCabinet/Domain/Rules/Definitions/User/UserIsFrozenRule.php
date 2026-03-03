<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\User;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Repository\BlockStateRepository;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

class UserIsFrozenRule extends Rule
{
    public function __construct(
        protected BlockStateRepository $blockStateRepo
    ){}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to UserIsNotFrozenRule');

        if ($context->getUser()->getBlockState() !== $this->blockStateRepo->findByCode('frozen'))
            return RuleResult::fail("Пользователь не является замороженным");

        return RuleResult::ok();
    }
}