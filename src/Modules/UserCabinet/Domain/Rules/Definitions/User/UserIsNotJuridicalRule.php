<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\User;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Repository\UserJurStateRepository;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

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