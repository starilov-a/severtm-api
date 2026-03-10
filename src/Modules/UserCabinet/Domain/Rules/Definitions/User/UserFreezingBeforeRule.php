<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\User;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

class UserFreezingBeforeRule extends Rule
{
    public function __construct(
        protected UserTaskRepositoryInterface $userTaskRepo,
        protected UserTaskTypeRepositoryInterface $userTaskTypeRepo,
    ) {}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        $oldTask = $this->userTaskRepo->findOneBy([
            'user' => $context->getUser(),
            'type' => $this->userTaskTypeRepo->findOneBy(['code' => 'freeze'])
        ]);

        if (!$oldTask)
            return RuleResult::fail("Пользователь ранее не замораживался");

        return RuleResult::ok();
    }
}