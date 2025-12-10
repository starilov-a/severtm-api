<?php

namespace App\Modules\Common\Domain\Service\Rules\User;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;

class UserIsNotActivatedContext implements HasUser
{
    public function __construct(
        protected User $user
    ){}
    public function getUser(): User
    {
        return $this->user;
    }

}