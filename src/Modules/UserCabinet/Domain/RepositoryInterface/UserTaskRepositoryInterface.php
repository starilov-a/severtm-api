<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTask;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskState;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserTaskType;

interface UserTaskRepositoryInterface extends RepositoryInterface
{
    public function hasTaskWithState(User $user, UserTaskType $type, UserTaskState $state): bool;
    public function hasTaskWithStateInPeriod(User $user, UserTaskType $type, UserTaskState $state, \DateTimeInterface $from, \DateTimeInterface $to): bool;
    public function save(UserTask $userTask): UserTask;
}
