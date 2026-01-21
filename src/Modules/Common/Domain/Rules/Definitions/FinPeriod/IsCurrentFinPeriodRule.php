<?php

namespace App\Modules\Common\Domain\Rules\Definitions\FinPeriod;

use App\Modules\Common\Domain\Contexts\Interfaces\HasFinPeriod;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class IsCurrentFinPeriodRule extends Rule
{
    public function __construct(
        protected LoggerService $loggerService,
    ){}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasFinPeriod))
            throw new \LogicException('Wrong context passed to IsCurrentFinPeriodRule');

        if (!$context->getFinPeriod()->isCurrent())
            return RuleResult::fail('Финансовый период не является текущим');

        return RuleResult::ok();
    }
}