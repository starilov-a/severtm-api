<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;

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