<?php

namespace App\Modules\Common\Domain\Service\Rules;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

abstract class Rule implements RuleInterface
{

    protected function getMasterId(): int
    {
        return UserSessionService::getUserId();
    }
}