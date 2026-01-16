<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\CreditHistory;
use App\Modules\Common\Domain\Repository\CreditHistoryRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreditHistoryLogDto;
use Doctrine\ORM\EntityManagerInterface;

class CreditHistoryService
{
    public function __construct(
        protected EntityManagerInterface $em,

        protected CreditHistoryRepository $creditHistoryRepo,
    ) {}
    public function createCreditHistoryLog(CreditHistoryLogDto $creditHistoryLogDto): CreditHistory
    {
        $creditHistoryLog = new CreditHistory();

        $creditHistoryLog->setCreditDeadline($creditHistoryLogDto->getCreditDeadline());
        $creditHistoryLog->setCreditBill($creditHistoryLogDto->getCreditBill());
        $creditHistoryLog->setUser($creditHistoryLogDto->getUser());
        $creditHistoryLog->setMaster($creditHistoryLogDto->getMaster());

        return $this->update($creditHistoryLog);
    }

    public function update(CreditHistory $creditHistoryLog): CreditHistory
    {
        $this->em->persist($creditHistoryLog);
        $this->em->flush();

        return $creditHistoryLog;
    }
}