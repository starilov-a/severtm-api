<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\Region;

interface RegionRepositoryInterface extends RepositoryInterface
{
    public function findByStrCode(string $strCode): ?Region;
}
