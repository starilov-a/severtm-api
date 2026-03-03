<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasMaster;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasStartFreezeDate;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\WebAction;

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
