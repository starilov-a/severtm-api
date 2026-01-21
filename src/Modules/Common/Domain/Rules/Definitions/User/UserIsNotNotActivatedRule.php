<?php

namespace App\Modules\Common\Domain\Rules\Definitions\User;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Repository\UserJurStateRepository;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class UserIsNotNotActivatedRule extends Rule
{
    public function __construct(
        protected UserJurStateRepository $jurStateRepo,
    ){}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        if ($context->getUser()->getJurState() === $this->jurStateRepo->findOneBy(['code' => 'legal_person_no_active']))
            return RuleResult::fail('Пользователь является неактивированным юриком');

        return RuleResult::ok();
    }
}