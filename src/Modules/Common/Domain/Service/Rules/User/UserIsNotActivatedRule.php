<?php

namespace App\Modules\Common\Domain\Service\Rules\User;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Rule;

class UserIsNotActivatedRule extends Rule
{

    public function check(object $context): bool
    {
        if (!$context instanceof HasUser)
            throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        if (!(bool) $context->getUser()->isJuridical())
            return false;

        return true;
    }
}