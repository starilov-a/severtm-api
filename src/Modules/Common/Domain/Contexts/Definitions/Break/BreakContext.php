<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\Break;

use App\Modules\Common\Domain\Contexts\Interfaces\HasCountAvailableBreaks;
use App\Modules\Common\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;

class BreakContext implements HasWebAction, HasMaster, HasUser, HasCountAvailableBreaks
{
    public function __construct(
        protected WebAction     $webAction,
        protected User          $master,
        protected User          $user,
        protected int           $countAvailableBreaks,
    ) {}
    public function getUser(): User
    {
        return $this->user;
    }

    public function getMaster(): User
    {
        return $this->master;
    }

    public function getWebAction(): WebAction
    {
        return $this->webAction;
    }

    public function getCountAvailableBreaks(): int
    {
        return $this->countAvailableBreaks;
    }
}