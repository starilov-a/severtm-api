<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\BlockHistory;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\BlockHistoryRepository;
use App\Modules\Common\Domain\Repository\UserRepository;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
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