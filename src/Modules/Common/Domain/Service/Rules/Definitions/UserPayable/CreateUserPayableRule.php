<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\UserPayable;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasPayableType;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasRefundFinPeriodNullable;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUserServMode;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class CreateUserPayableRule extends Rule
{

    /**
     * @param object $context
     * @return bool
     */
    public function check(object $context = null): RuleResult
    {
        if (
            !($context instanceof HasPayableType) ||
            !($context instanceof HasUserServMode) ||
            !($context instanceof HasRefundFinPeriodNullable) ||
            !($context instanceof HasIsAppliedWriteOff) ||
            !($context instanceof HasIsRealWriteOff)
        ) throw new \LogicException('Wrong context passed to CreateUserPayableRule');


        $typeString = $context->getPayableType()->getCode();
        $userServMode = $context->getUserServMode();
        $refundFinPeriod = $context->getRefundFinPeriodNullable();
        $isReal = $context->getIsRealWriteOff();
        $isApplied = $context->getIsAppliedWriteOff();



        if (!in_array($typeString, ['write_off', 'refund', 'correct']) && $userServMode->getId() == 0 ||
            $typeString == 'write_off' && !$userServMode->getFinPeriod()->isCurrent() ||
            in_array($typeString, ['write_off', /*'refund' ,*/ 'correct']) && $userServMode->getId() > 0 || // убрал рефанд, тк есть смысл показывать за что возврат
            $typeString == 'refund' && !isset($refundFinPeriod) ||
            $typeString != 'refund' && isset($refundFinPeriod) ||
            !$isReal && !$isApplied
        ) {
            dd(!in_array($typeString, ['write_off', 'refund', 'correct']) && $userServMode->getId() == 0 ,
                $typeString == 'write_off' && !$userServMode->getFinPeriod()->isCurrent() ,
                in_array($typeString, ['write_off', /*'refund' ,*/ 'correct']) && $userServMode->getId() > 0 , // убрал рефанд, тк есть смысл показывать за что возврат
                $typeString == 'refund' && !isset($refundFinPeriod) ,
                $typeString != 'refund' && isset($refundFinPeriod) ,
                !$isReal && !$isApplied,
                $refundFinPeriod,
                $userServMode);
            return RuleResult::fail('Ошибка параметров платежа при списании');
        }

        return RuleResult::ok();
    }
}