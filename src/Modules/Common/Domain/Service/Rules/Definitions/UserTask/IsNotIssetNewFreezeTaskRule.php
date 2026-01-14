<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\UserTask;

use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class IsNotIssetNewFreezeTaskRule extends Rule
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

        if (!$userTasksCollection->isEmpty())
            return RuleResult::fail('Задача на создание заморозки уже существует!');

        return RuleResult::ok();
    }
}