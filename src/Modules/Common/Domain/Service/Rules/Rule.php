<?php

namespace App\Modules\Common\Domain\Service\Rules;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

abstract class Rule implements RuleInterface
{

}