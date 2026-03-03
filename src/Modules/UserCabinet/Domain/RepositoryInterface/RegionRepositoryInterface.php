<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Region;

interface RegionRepositoryInterface extends RepositoryInterface
{
    public function findByStrCode(string $strCode): ?Region;
}
