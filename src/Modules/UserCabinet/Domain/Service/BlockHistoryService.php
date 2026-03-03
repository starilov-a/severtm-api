<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\BlockHistory;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\BlockHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;

class BlockHistoryService
{
    public function __construct(
        protected BlockHistoryRepositoryInterface    $blockHistoryRepo,
        protected UserRepositoryInterface            $userRepo,
    ) {}

    public function writeBlockLog(User $user, $isBlocking = true, $comment = null): BlockHistory
    {
        $master = $this->userRepo->find(UserSessionService::getUserId());

        $historyBlockLog = new BlockHistory();

        $historyBlockLog->setBlockDate(new \DateTime());
        $historyBlockLog->setBlockOld(!$isBlocking); // Старый статус блокировки
        $historyBlockLog->setBlockStatus($isBlocking); // Новый статус блокировки
        $historyBlockLog->setUser($user);
        $historyBlockLog->setMaster($master);
        $historyBlockLog->setBlockComments($comment);

        return $this->blockHistoryRepo->save($historyBlockLog);
    }



}
