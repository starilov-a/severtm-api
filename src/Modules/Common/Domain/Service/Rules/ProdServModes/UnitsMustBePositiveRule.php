<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMasterId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasModeUnitCount;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Domain\Service\Rules\RuleInterface;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class UnitsMustBePositiveRule extends Rule
{

    public function check(object $context): bool
    {
        if (!$context instanceof HasUserId || !$context instanceof HasActionId || !$context instanceof HasModeUnitCount) {
            throw new \LogicException('Wrong context passed to UnitsMustBePositiveRule');
        }

        // Основная бизнес логика
        if ($context->getModeUnitCount() < 1) {
            throw new ImportantBusinessException($context->getUserId(), $context->getActionId(),'Указано некорректное кол-во услуг');
        }

        return true;
    }
}