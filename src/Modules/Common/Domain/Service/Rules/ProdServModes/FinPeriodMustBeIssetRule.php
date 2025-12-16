<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class FinPeriodMustBeIssetRule extends Rule
{

    public function check(object $context): bool
    {
        //TODO стоит перенести в папку FinPeriod
        if (!$context instanceof HasUserId || !$context instanceof HasActionId || !$context instanceof HasNullbleVar) {
            throw new \LogicException('Wrong context passed to FinPeriodMustBeIssetRule');
        }

        // Основная бизнес логика
        if ($context->getNullbleVar() === null)
            throw new ImportantBusinessException($context->getUserId(), $context->getActionId(),'Не указан финансовый период');

        return true;
    }
}