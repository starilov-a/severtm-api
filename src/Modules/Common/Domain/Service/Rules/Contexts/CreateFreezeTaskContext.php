<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;

class CreateFreezeTaskContext implements HasUser, HasWebAction, HasStartFreezeDate
{
    public function __construct(
        protected WebAction $webAction,
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
}
