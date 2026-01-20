<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\User;

use App\Modules\Common\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;

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