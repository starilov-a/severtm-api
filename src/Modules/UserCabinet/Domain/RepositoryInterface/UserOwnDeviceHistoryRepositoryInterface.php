<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserOwnDeviceHistory;

interface UserOwnDeviceHistoryRepositoryInterface extends RepositoryInterface
{
    public function save(UserOwnDeviceHistory $userOwnDeviceHistory): UserOwnDeviceHistory;
}
