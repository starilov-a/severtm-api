<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\UserJurStateRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\UserJurState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserJurState|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserJurState|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserJurState[]    findAll()
 * @method UserJurState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserJurStateRepository extends ServiceEntityRepository implements UserJurStateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserJurState::class);
    }
}
