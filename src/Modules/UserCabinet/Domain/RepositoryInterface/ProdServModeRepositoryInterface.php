<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Dto\Request\ServModeFilterDto;
use App\Modules\UserCabinet\Domain\Entity\ProdServMode;

interface ProdServModeRepositoryInterface extends RepositoryInterface
{
    public function getModeByFilter(ServModeFilterDto $filter): array;
    public function findVisibleByServiceCode(string $serviceStrCode): array;
    public function findOneByStrCode(string $modeCode): ?ProdServMode;
    public function hasGroup(int $prodServModeId, string $groupString): bool;
    public function isAvailableForRegionByCode(int $prodServModeId, string $regionCode): bool;
}
