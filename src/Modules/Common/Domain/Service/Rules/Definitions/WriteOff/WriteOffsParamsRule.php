<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\WriteOff;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPayableType;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRefundFinPeriod;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserServMode;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class WriteOffsParamsRule extends Rule
{

    /**
     * @param object $context
     * @return bool
     */
    public function check(object $context): RuleResult
    {
        if (
            !$context instanceof HasActionId ||
            !$context instanceof HasUser ||
            !$context instanceof HasPayableType ||
            !$context instanceof HasUserServMode ||
            !$context instanceof HasRefundFinPeriod ||
            !$context instanceof HasIsAppliedWriteOff ||
            !$context instanceof HasIsRealWriteOff
        ) throw new \LogicException('Wrong context passed to WriteOffsParamsRule');


        $type = $context->getWriteOffType();
        $userServMode = $context->getUserServMode();
        $refundFinPeriod = $context->getRefundFinPeriod();
        $isReal = $context->getIsRealWriteOff();
        $isApplied = $context->getIsAppliedWriteOff();

        if (!in_array($type, ['write_off', 'refund', 'correct']) && $userServMode->getId() == 0 ||
            $type == 'write_off' && !$userServMode->getFinPeriod()->isCurrent() ||
            in_array($type, ['write_off', 'refund', 'correct']) && $userServMode->getId() > 0 ||
            $type == 'refund' && isset($refundFinPeriod) ||
            $type != 'refund' && !isset($refundFinPeriod) ||
            !$isReal && !$isApplied
        ) return RuleResult::fail('Ошибка параметров платежа при списании');

        return RuleResult::ok();
    }
}