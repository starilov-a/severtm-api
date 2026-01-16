<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Break;

use App\Modules\Common\Domain\Repository\ConfigRepository;
use App\Modules\Common\Domain\Service\BreakService;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class HasNoAvailableBreaksRule extends Rule
{
    public function __construct(
        protected BreakService $breakService,
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to HasNoAvailableBreaksRule');

        if ($this->breakService->countAvailableBreaksForUser($context->getUser()) <= 0)
            return RuleResult::fail('Нет доступных отсрочек');

        return RuleResult::ok();
    }
}