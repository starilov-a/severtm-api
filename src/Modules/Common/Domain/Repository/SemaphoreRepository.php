<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\Semaphore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Semaphore|null find($id, $lockMode = null, $lockVersion = null)
 * @method Semaphore|null findOneBy(array $criteria, array $orderBy = null)
 * @method Semaphore[]    findAll()
 * @method Semaphore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemaphoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semaphore::class);
    }
}

