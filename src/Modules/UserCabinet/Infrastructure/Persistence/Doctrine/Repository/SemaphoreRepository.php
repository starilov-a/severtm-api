<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\SemaphoreRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\Semaphore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Semaphore|null find($id, $lockMode = null, $lockVersion = null)
 * @method Semaphore|null findOneBy(array $criteria, array $orderBy = null)
 * @method Semaphore[]    findAll()
 * @method Semaphore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemaphoreRepository extends ServiceEntityRepository implements SemaphoreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semaphore::class);
    }
}

