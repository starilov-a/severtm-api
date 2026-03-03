<?php

namespace App\Modules\UserCabinet\Domain\RepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\WebAction;

interface WebActionRepositoryInterface extends RepositoryInterface
{
    public function findIdByCid(string $cid): ?WebAction;
}
