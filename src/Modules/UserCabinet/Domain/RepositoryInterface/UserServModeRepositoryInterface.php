<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\FinPeriod;
use App\Modules\UserCabinet\Domain\Entity\ProdServMode;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Entity\UserServMode;

interface UserServModeRepositoryInterface extends RepositoryInterface
{
    public function hasActiveForUserAndServiceCode(int $uid, int $fid, string $serviceCode): bool;
    public function clearForUserAndServiceCode(int $uid, int $fid, string $serviceCode): int;
    public function findCurrentModesWithService(User $user): array;
    public function findActiveByModeAndUser(ProdServMode $mode, User $user, ?FinPeriod $finPeriod = null): ?UserServMode;
    public function hasActiveMultiPeriodModes(User $user, FinPeriod $finPeriod): bool;
    public function findCurrentActiveModes(User $user): array;
    public function hasRentNow(int $userId): bool;
    public function findActiveTariffsByUser(User $user): array;
    public function findActiveTariffsByUserAndFinPeriod(User $user, FinPeriod $finPeriod): UserServMode;
    public function save(UserServMode $userServMode): UserServMode;
}
