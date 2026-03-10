<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserOwnDeviceHistory;

interface UserOwnDeviceHistoryRepositoryInterface extends RepositoryInterface
{
    public function save(UserOwnDeviceHistory $userOwnDeviceHistory): UserOwnDeviceHistory;
}
