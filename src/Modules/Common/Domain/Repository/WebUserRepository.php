<?php

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\BaseRepository;
use App\Modules\Common\Domain\Entity\WebUser;
use Doctrine\Persistence\ManagerRegistry;


class WebUserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebUser::class);
    }

}
