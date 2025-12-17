<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

/**
 * Бизнес-правило:
 * не плодить несколько запланированных заморозок, если уже существует
 */
class NoExistingNewFreezeTaskRule extends Rule
{
    public function __construct(
        protected UserTaskRepository $userTaskRepo,
        protected UserTaskStateRepository $taskStateRepo,
        protected UserTaskTypeRepository $taskTypeRepo,
    ) {}

    /** @var HasUser & HasWebAction $context */
    public function check(object $context): bool
    {
        if (!($context instanceof HasUser) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to NoExistingNewFreezeTaskRule');

        $issetTask = $this->userTaskRepo->hasTaskWithState(
            $context->getUser(),
            $this->taskTypeRepo->findOneBy(['code' => 'freeze']),
            $this->taskStateRepo->findOneBy(['code' => 'new'])
        );

        if ($issetTask) {
            throw new ImportantBusinessException(
                $this->getMasterId(),
                $context->getWebAction()->getId(),
                'Задача на заморозку уже создана и ожидает выполнения'
            );
        }

        return true;
    }
}
