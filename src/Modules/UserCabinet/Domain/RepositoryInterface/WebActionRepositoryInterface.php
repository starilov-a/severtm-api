<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebAction;

interface WebActionRepositoryInterface extends RepositoryInterface
{
    public function findIdByCid(string $cid): ?WebAction;
}
