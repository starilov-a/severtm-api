<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasCountAvailableBreaks;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;

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