<?php

namespace App\Modules\Common\Domain\Rules\Definitions\User;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

/**
 * Бизнес-правило:
 * Клиент не должен быть заблокирован
 */
class UserMustNotBeBlockedRule extends Rule
{
    public function __construct(
        protected BlockStateRepository $blockStateRepo,
    ){}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        if ($context->getUser()->getBlockState() === $this->blockStateRepo->findByCode('blocked'))
            return RuleResult::fail("Пользователь является заблокированным");

        return RuleResult::ok();
    }
}