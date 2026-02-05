<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Device;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserOwnDeviceHistory;
use App\Modules\Common\Domain\Repository\UserOwnDeviceHistoryRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;

class UserOwnDeviceHistoryService
{
    public function __construct(
        protected UserOwnDeviceHistoryRepository $userOwnDeviceHistoryRepo,
        protected UserRepository $userRepo
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

        return $this->save($log);
    }

    protected function save(UserOwnDeviceHistory $log): UserOwnDeviceHistory
    {
        $em = $this->userOwnDeviceHistoryRepo->getEntityManager();
        $em->persist($log);
        $em->flush();

        return $this->userOwnDeviceHistoryRepo->save($log);
    }
}