<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Dto\Request\TariffFilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Tariff;

interface TariffRepositoryInterface extends RepositoryInterface
{
    public function getTariffs(TariffFilterDto $dto);
    public function getCurrentForUser(int $uid): ?Tariff;
    public function getNextForUser(int $uid): ?Tariff;
    public function belongsToGroupCode(int $tariffId, string $groupCode): bool;
    public function clearAssignedTariffs(int $uid, int $fid): bool;
    public function setNextTariffForClient(int $fid, int $userId, int $tariffId): bool;
}
