<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserTask;
use App\Modules\UserCabinet\Domain\Entity\UserTaskState;
use App\Modules\UserCabinet\Domain\Entity\UserTaskType;

interface UserTaskRepositoryInterface extends RepositoryInterface
{
    public function hasTaskWithState(User $user, UserTaskType $type, UserTaskState $state): bool;
    public function hasTaskWithStateInPeriod(User $user, UserTaskType $type, UserTaskState $state, \DateTimeInterface $from, \DateTimeInterface $to): bool;
    public function save(UserTask $userTask): UserTask;
}
