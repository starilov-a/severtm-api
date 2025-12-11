<?php

namespace App\Modules\Common\Domain\Service\Rules\User;

use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UserIsNotActivatedRule extends Rule
{
    public function __construct(
        protected LoggerService $loggerService
    ){}

    public function check(object $context): bool
    {
        if (
            !$context instanceof HasUser ||
            !$context instanceof HasMaster
        )
            throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        if ($context->getUser()->isJuridical() != 2) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $context->getMaster()->getId(),
                1,
                'Пользователь является не активированным юриком',
                true
            ));

            return false;
        }

        return true;
    }
}