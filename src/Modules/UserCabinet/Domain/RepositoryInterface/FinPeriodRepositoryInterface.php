<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;

interface FinPeriodRepositoryInterface extends RepositoryInterface
{
    public function getCurrent(): ?FinPeriod;
    public function findFirstFutureFidWithUserModes(int $userId, int $baseFid): ?int;
    public function isFidInFuture(int $fid): bool;
    public function cleanFutureFromFinId(int $userId, int $fromFid): int;
    public function clearForFinPeriod(int $fid, int $userId): ?bool;
}
