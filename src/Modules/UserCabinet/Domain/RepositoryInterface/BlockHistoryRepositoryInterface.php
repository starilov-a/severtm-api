<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\BlockHistory;

interface BlockHistoryRepositoryInterface extends RepositoryInterface
{
    public function save(BlockHistory $blockHistoryLog): BlockHistory;
}
