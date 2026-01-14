<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Repository\BlockStateRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class IsNotFrozenRule extends Rule
{
    public function __construct(
        protected BlockStateRepository $blockStateRepo,
    ) {}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to IsNotFrozenRule');

        if ($context->getUser()->getBlockState() !== $this->blockStateRepo->findByCode('frozen'))
            return RuleResult::fail('Пользователь не является замороженным');

        return RuleResult::ok();
    }
}