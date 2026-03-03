<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\User;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\WebAction;

class UserContext implements HasUser, HasMaster, HasWebAction
{
    public function __construct(
        protected WebAction     $webAction,
        protected User          $master,
        protected User          $user,
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
}