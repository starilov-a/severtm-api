<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\BlockState;

interface BlockStateRepositoryInterface extends RepositoryInterface
{
    public function findByCode(string $code): ?BlockState;
}
