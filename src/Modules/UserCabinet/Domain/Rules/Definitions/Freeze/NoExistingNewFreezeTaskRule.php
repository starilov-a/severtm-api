<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Repository\UserTaskRepository;
use App\Modules\UserCabinet\Domain\Repository\UserTaskStateRepository;
use App\Modules\UserCabinet\Domain\Repository\UserTaskTypeRepository;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

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
