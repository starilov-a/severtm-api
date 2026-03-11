<?php

namespace App\Modules\BuildermanCabinet\Domain\Policy\ApplictionStatus;

use App\Modules\Common\Domain\Policy\Policy;
use App\Modules\BuildermanCabinet\Domain\Context\Interfaces\HasApplicationStatusInterface;

class ApplicationStatusesAvailableForBuilderListPolicy extends Policy
{

    public function isAllowed(object $context): bool
    {
        if (!($context instanceof HasApplicationStatusInterface))
            throw new \LogicException('Wrong context passed to ApplicationStatusesAvailableForBuilderListPolicy');

        // Если статус не относится к указанным, то вылетаем
        if (!in_array($context->getApplicationStatus()->getStrCode(), ['connection', 'transfer', 'uncallable', 'uncalled', 'problem']))
            return false;

        return true;
    }
}