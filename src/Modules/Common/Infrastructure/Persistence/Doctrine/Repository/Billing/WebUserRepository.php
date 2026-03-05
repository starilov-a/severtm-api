<?php

namespace App\Modules\Common\Infrastructure\Persistence\Doctrine\Repository\Billing;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\WebUser;
use Doctrine\Persistence\ManagerRegistry;


class WebUserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebUser::class);
    }

}
