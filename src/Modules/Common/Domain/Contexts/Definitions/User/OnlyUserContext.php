<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\User;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Entity\User;

class OnlyUserContext implements HasUser
{
    public function __construct(
        protected User          $user,
    ) {}
    public function getUser(): User
    {
        return $this->user;
    }

}