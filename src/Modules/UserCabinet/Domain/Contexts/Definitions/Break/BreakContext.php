<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\Break;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasCountAvailableBreaks;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebAction;

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