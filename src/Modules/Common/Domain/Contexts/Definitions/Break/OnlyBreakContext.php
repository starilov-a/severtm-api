<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\Break;

use App\Modules\Common\Domain\Contexts\Interfaces\HasCountAvailableBreaks;
use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Entity\User;

class OnlyBreakContext implements HasUser, HasCountAvailableBreaks
{
    public function __construct(
        protected User          $user,
        protected int           $countAvailableBreaks,
    ) {}
    public function getUser(): User
    {
        return $this->user;
    }

    public function getCountAvailableBreaks(): int
    {
        return $this->countAvailableBreaks;
    }
}