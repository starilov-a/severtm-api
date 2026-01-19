<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasCountAvailableBreaks;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;

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