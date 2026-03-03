<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Tariff;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\TariffFilterDto;

interface TariffRepositoryInterface extends RepositoryInterface
{
    public function getTariffs(TariffFilterDto $dto);
    public function getCurrentForUser(int $uid): ?Tariff;
    public function getNextForUser(int $uid): ?Tariff;
    public function belongsToGroupCode(int $tariffId, string $groupCode): bool;
    public function clearAssignedTariffs(int $uid, int $fid): bool;
    public function setNextTariffForClient(int $fid, int $userId, int $tariffId): bool;
}
