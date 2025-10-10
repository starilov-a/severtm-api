<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Modules\UserCabinet\Entity\WebAction;

final class WebActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebAction::class);
    }

    public function findIdByCid(string $cid): ?WebAction
    {
        // TODO:: Добавить исключение DB
        return $this->findOneBy(['cid' => $cid]);
    }
}
