<?php

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\WebUserRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\WebUser;
use Doctrine\Persistence\ManagerRegistry;


class WebUserRepository extends BaseRepository implements WebUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebUser::class);
    }

}
