<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

/**
 * Бизнес-правило:
 * не плодить несколько запланированных заморозок, если уже существует
 */
class NoExistingNewFreezeTaskRule extends Rule
{
    public function __construct(
        private UserTaskRepository $userTaskRepository,
    ) {
    }

    public function check(object $context): bool
    {
        if (!$context instanceof CreateFreezeTaskContext) {
            throw new \LogicException('Wrong context passed to NoExistingNewFreezeTaskRule');
        }

        if ($this->userTaskRepository->hasTaskWithState(
            $context->getUser(),
            $context->getFreezeTaskType(),
            $context->getNewState()
        )) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                'Задача на заморозку уже создана и ожидает выполнения'
            );
        }

        return true;
    }
}
