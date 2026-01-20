<?php

namespace App\Modules\Common\Domain\Rules\Definitions\UserPayable;

use App\Modules\Common\Domain\Contexts\Interfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Contexts\Interfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Contexts\Interfaces\HasPayableType;
use App\Modules\Common\Domain\Contexts\Interfaces\HasRefundFinPeriodNullable;
use App\Modules\Common\Domain\Contexts\Interfaces\HasUserServMode;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

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