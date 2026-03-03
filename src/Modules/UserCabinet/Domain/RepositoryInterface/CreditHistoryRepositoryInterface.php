<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\CreditHistory;
use App\Modules\UserCabinet\Domain\Entity\FinPeriod;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\CreditHistoryLogDto;

interface CreditHistoryRepositoryInterface extends RepositoryInterface
{
    public function findAllForFinPeriod(FinPeriod $finPeriod): array;
    public function hasAnyForFinPeriodForUser(User $user, FinPeriod $finPeriod): bool;
    public function insertLog(CreditHistoryLogDto $dto): int;
    public function countByUser(User $user): int;
    public function countByUserId(int $userId): int;
}
