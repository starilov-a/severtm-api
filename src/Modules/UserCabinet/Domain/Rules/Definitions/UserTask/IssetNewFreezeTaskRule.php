<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\UserTask;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

class IssetNewFreezeTaskRule extends Rule
{
    public function __construct(
        protected UserTaskStateRepositoryInterface $taskStateRepo,
        protected UserTaskTypeRepositoryInterface $taskTypeRepo,
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to IsNotIssetNewFreezeTaskRule');

        $userTasksCollection = $context->getUser()->getTasksByStateAndType(
            $this->taskStateRepo->findOneBy(['code' => 'new']),
            $this->taskTypeRepo->findOneBy(['code' => 'freeze'])
        );

        if ($userTasksCollection->isEmpty())
            return RuleResult::fail('Задачи на создание заморозки не существует');

        return RuleResult::ok();
    }
}