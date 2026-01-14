<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\User;

use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class UserFreezingBeforeRule extends Rule
{
    public function __construct(
        protected UserTaskRepository $userTaskRepo,
        protected UserTaskTypeRepository $userTaskTypeRepo,
    ) {}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        $oldTask = $this->userTaskRepo->findOneBy([
            'user' => $context->getUser(),
            'state' => $this->userTaskTypeRepo->findOneBy(['code' => 'freeze'])
        ]);

        if (!$oldTask)
            return RuleResult::fail("Пользователь ранее не замораживался");

        return RuleResult::ok();
    }
}