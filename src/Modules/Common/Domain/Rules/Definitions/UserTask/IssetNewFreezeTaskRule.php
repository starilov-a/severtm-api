<?php

namespace App\Modules\Common\Domain\Rules\Definitions\UserTask;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class IssetNewFreezeTaskRule extends Rule
{
    public function __construct(
        protected UserTaskStateRepository $taskStateRepo,
        protected UserTaskTypeRepository $taskTypeRepo,
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