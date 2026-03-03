<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\BlockHistoryRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\BlockHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlockHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlockHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlockHistory[]    findAll()
 * @method BlockHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockHistoryRepository extends ServiceEntityRepository implements BlockHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockHistory::class);
    }

    public function save(BlockHistory $blockHistoryLog): BlockHistory
    {
        $this->getEntityManager()->persist($blockHistoryLog);
        $this->getEntityManager()->flush();

        return $blockHistoryLog;
    }
}

