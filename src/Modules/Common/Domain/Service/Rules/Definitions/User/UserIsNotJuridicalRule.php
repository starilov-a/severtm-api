<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\User;

use App\Modules\Common\Domain\Repository\UserJurStateRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class UserIsNotJuridicalRule extends Rule
{
    public function __construct(
        protected UserJurStateRepository $userJurStateRepo
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserIsNotActivatedRule');

        // не физик
        if (!$context->getUser()->getJurState() == $this->userJurStateRepo->findOneBy(['code' => 'natural_person']))
            return RuleResult::fail('Пользователь не является физическим лицом');

        return RuleResult::ok();
    }
}