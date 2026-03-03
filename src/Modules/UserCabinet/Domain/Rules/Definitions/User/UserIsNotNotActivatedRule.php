<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\User;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserJurStateRepositoryInterface;

class UserIsNotNotActivatedRule extends Rule
{
    public function __construct(
        protected UserJurStateRepositoryInterface $jurStateRepo,
    ){}

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        if ($context->getUser()->getJurState() === $this->jurStateRepo->findOneBy(['code' => 'legal_person_no_active']))
            return RuleResult::fail('Пользователь является неактивированным юриком');

        return RuleResult::ok();
    }
}