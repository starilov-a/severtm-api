<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserOwnDevice;

interface UserOwnDeviceRepositoryInterface extends RepositoryInterface
{
    public function save(UserOwnDevice $userOwnDevice): UserOwnDevice;
    public function remove(UserOwnDevice $userOwnDevice): void;
}
