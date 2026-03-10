<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\BlockHistory;

interface BlockHistoryRepositoryInterface extends RepositoryInterface
{
    public function save(BlockHistory $blockHistoryLog): BlockHistory;
}
