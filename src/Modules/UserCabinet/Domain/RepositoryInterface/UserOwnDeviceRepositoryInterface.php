<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserOwnDevice;

interface UserOwnDeviceRepositoryInterface extends RepositoryInterface
{
    public function save(UserOwnDevice $userOwnDevice): UserOwnDevice;
    public function remove(UserOwnDevice $userOwnDevice): void;
}
