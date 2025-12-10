<?php

namespace App\Modules\Common\Domain\Service\Rules\ProdServModes;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasBoolVar;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasProdServMode;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

class JurStatusIsCorrectRule extends Rule
{

    public function check(object $context): bool
    {
        if (!$context instanceof HasUserId || !$context instanceof HasActionId
            || !$context instanceof HasProdServMode || !$context instanceof HasBoolVar) {
            throw new \LogicException('Wrong context passed to JurStatusIsCorrectRule');
        }

        if ($context->getBoolVar() !== (bool) $context->getMode()->isJuridical()) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                'Юридический признак не совпадает с признаком режима'
            );
        }

        return true;
    }
}