<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMasterId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\RuleInterface;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class FinPeriodMustBeIssetRule implements RuleInterface
{

    public function check(object $context): void
    {
        if (!$context instanceof HasMasterId || !$context instanceof HasActionId || !$context instanceof HasNullbleVar) {
            throw new \LogicException('Wrong context passed to UnitsMustBePositiveRule');
        }

        // Основная бизнес логика
        if ($context->getNullbleVar() === null) {
            throw new ImportantBusinessException($context->getMasterId(), $context->getActionId(),'Не указан финансовый период');
        }
    }
}