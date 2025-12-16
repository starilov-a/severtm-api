<?php

namespace App\Modules\Common\Domain\Service\Rules\User;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use Psr\Log\LoggerInterface;

class UserMustNotBeBlockedRule extends Rule
{
    public function __construct(
        protected LoggerService $loggerService,
    ){}
    public function check(object $context): bool
    {
        if (
            !($context instanceof HasUser) ||
            !($context instanceof HasMaster) ||
            !($context instanceof HasActionId)
        )
            throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        if ($context->getUser()->get) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                "Пользователь является заблокированным"
            );
        }

        return true;
    }
}