<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findAllContractIds(User $user): array;
    public function save(User $user): User;
}
