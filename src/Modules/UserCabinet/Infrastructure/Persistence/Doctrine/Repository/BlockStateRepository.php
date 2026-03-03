<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\UserCabinet\Domain\RepositoryInterface\BlockStateRepositoryInterface;

use App\Modules\UserCabinet\Domain\Entity\BlockState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class BlockStateRepository extends ServiceEntityRepository implements BlockStateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockState::class);
    }

    public function findByCode(string $code): ?BlockState
    {
        return $this->findOneBy(['code' => $code]);
    }
}
