<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserTaskState;
use App\Modules\Common\Domain\Entity\UserTaskType;
use App\Modules\Common\Domain\Entity\WebAction;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasMaster;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasWebAction;

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
