<?php

namespace App\Modules\Common\Domain\Service\Rules\WriteOff;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasIsAppliedWriteOff;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasIsRealWriteOff;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasPayableType;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasRefundFinPeriod;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserServMode;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class WriteOffsParamsRule extends Rule
{

    /**
     * @param object $context
     * @return bool
     */
    public function check(object $context): bool
    {
        if (
            !$context instanceof HasActionId ||
            !$context instanceof HasUser ||
            !$context instanceof HasPayableType ||
            !$context instanceof HasUserServMode ||
            !$context instanceof HasRefundFinPeriod ||
            !$context instanceof HasIsAppliedWriteOff ||
            !$context instanceof HasIsRealWriteOff
        )
            throw new \LogicException('Wrong context passed to WriteOffsParamsRule');


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
        ) {
            throw new ImportantBusinessException(
                $context->getUser()->getId(),
                $context->getActionId(),
                'Ошибка параметров платежа при списании'
            );
        }





        return true;
    }
}