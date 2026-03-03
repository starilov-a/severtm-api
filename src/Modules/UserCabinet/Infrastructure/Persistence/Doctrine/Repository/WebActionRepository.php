<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\WebActionRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\WebAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class WebActionRepository extends ServiceEntityRepository implements WebActionRepositoryInterface
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
