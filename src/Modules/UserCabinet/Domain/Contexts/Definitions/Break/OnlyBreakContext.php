<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\Break;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasCountAvailableBreaks;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;

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