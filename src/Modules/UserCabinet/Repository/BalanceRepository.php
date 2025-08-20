<?php

namespace App\Modules\UserCabinet\Repository;

use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\Balance;
use App\Modules\UserCabinet\Service\Exception\UserNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class BalanceRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Balance::class);
    }
}