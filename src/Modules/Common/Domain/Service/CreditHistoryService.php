<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Repository\CreditHistoryRepository;
use App\Modules\Common\Domain\Service\Dto\Request\CreditHistoryLogDto;

class CreditHistoryService
{
    public function __construct(
        protected CreditHistoryRepository $creditHistoryRepo,
    ) {}

    /**
     * Создать запись в истории кредитов (credits_history) без ORM-сущности.
     *
     * Таблица не имеет PK и использует datetime/date поля, которые неудобны для Doctrine identity map,
     * поэтому пишем через DBAL insert.
     */
    public function createCreditHistoryLog(CreditHistoryLogDto $creditHistoryLogDto): int
    {
        return $this->creditHistoryRepo->insertLog($creditHistoryLogDto);
    }
}
