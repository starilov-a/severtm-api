<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\BlockState;

interface BlockStateRepositoryInterface extends RepositoryInterface
{
    public function findByCode(string $code): ?BlockState;
}
