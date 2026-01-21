<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\Freeze;

use App\Modules\Common\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\Common\Domain\Contexts\Interfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;

class CreateFreezeTaskContext implements HasUser, HasWebAction, HasStartFreezeDate, HasMaster
{
    public function __construct(
        protected WebAction $webAction,
        protected User $master,
        protected User $user,
        protected \DateTimeImmutable $startDate,
    ) {}

    public function getWebAction(): WebAction
    {
        return $this->webAction;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getStartFreezeDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getMaster(): User
    {
        return $this->master;
    }
}
