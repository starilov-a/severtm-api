<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\Device;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserOwnDeviceHistory;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserOwnDeviceHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class UserOwnDeviceHistoryService
{
    public function __construct(
        protected UserOwnDeviceHistoryRepositoryInterface $userOwnDeviceHistoryRepo,
        protected UserRepositoryInterface $userRepo
    ){}
    public function addHistoryLog(
        Device $device,
        User $user,
        string $comment,
        string $tag,
    ): UserOwnDeviceHistory
    {
        $log = new UserOwnDeviceHistory();

        $log->setDevice($device);
        $log->setUser($user);
        $log->setTag($tag);
        $log->setTimeStamp(new \DateTimeImmutable);
        $log->setDeviceComment($comment);
        $log->setMasterUser($this->userRepo->find(UserSessionService::getUserId()));
        $log->setDeviceNum($device->getSerialNumber());

        return $this->userOwnDeviceHistoryRepo->save($log);
    }
}
