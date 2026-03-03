<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\User;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Entity\User;

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