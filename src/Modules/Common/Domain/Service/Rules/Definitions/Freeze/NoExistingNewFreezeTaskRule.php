<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
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
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to NoExistingNewFreezeTaskRule');

        $issetTask = $this->userTaskRepo->hasTaskWithState(
            $context->getUser(),
            $this->taskTypeRepo->findOneBy(['code' => 'freeze']),
            $this->taskStateRepo->findOneBy(['code' => 'new'])
        );

        if ($issetTask)
            return RuleResult::fail('Задача на заморозку уже создана и ожидает выполнения');

        return RuleResult::ok();
    }
}
