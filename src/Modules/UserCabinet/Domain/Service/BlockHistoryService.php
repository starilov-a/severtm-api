<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\Entity\BlockHistory;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Repository\BlockHistoryRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use Doctrine\ORM\EntityManagerInterface;

class BlockHistoryService
{
    public function __construct(
        protected EntityManagerInterface    $em,

        protected BlockHistoryRepository    $blockHistoryRepo,
        protected UserRepository            $userRepo,
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

        return $this->save($historyBlockLog);
    }

    protected function save(BlockHistory $blockHistoryLog)
    {
        $this->em->persist($blockHistoryLog);
        $this->em->flush();

        return $blockHistoryLog;
    }

}