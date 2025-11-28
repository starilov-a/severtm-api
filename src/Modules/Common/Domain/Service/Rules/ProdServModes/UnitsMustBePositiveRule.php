<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMasterId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasModeUnitCount;
use App\Modules\Common\Domain\Service\Rules\RuleInterface;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class UnitsMustBePositiveRule implements RuleInterface
{

    public function check(object $context): void
    {
        if (!$context instanceof HasMasterId || !$context instanceof HasActionId || !$context instanceof HasModeUnitCount) {
            throw new \LogicException('Wrong context passed to UnitsMustBePositiveRule');
        }

        // Основная бизнес логика
        if ($context->getModeUnitCount() < 1) {
            throw new ImportantBusinessException($context->getMasterId(), $context->getActionId(),'Указано некорректное кол-во услуг');
        }
    }
}