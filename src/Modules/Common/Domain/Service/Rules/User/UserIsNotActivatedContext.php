<?php

namespace App\Modules\Common\Domain\Service\Rules\User;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUserId;

class UserIsNotActivatedContext implements HasUser, HasMaster
{
    public function __construct(
        protected User $master,
        protected User $user
    ){}
    public function getUser(): User
    {
        return $this->user;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

}