<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;

/**
 * Бизнес-правило:
 * не плодить несколько запланированных заморозок, если уже существует
 */
class NoExistingNewFreezeTaskRule extends Rule
{
    public function __construct(
        protected UserTaskRepositoryInterface $userTaskRepo,
        protected UserTaskStateRepositoryInterface $taskStateRepo,
        protected UserTaskTypeRepositoryInterface $taskTypeRepo,
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
