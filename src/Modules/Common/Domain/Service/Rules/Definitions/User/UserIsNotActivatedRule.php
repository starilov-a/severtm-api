<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\User;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UserIsNotActivatedRule extends Rule
{
    public function __construct(
        protected LoggerService $loggerService
    ){}

    public function check(object $context): RuleResult
    {
        if (
            !$context instanceof HasUser ||
            !$context instanceof HasMaster
        )
            throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        if ($context->getUser()->isJuridical() == 2)
            RuleResult::fail('Пользователь является не активированным юриком');

        return RuleResult::ok();
    }
}