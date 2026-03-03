<?php

namespace App\Modules\UserCabinet\Domain\Service;

use App\Modules\UserCabinet\Domain\RepositoryInterface\CreditHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\CreditHistoryLogDto;

class CreditHistoryService
{
    public function __construct(
        protected CreditHistoryRepositoryInterface $creditHistoryRepo,
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
