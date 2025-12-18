<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;

class UserIsNotActivatedContext implements HasUser, HasMaster
{
    public function __construct(
        protected User $master,
        protected User $user
    ) {}
    public function getUser(): User
    {
        return $this->user;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

}