<?php

namespace App\Modules\BuildermanCabinet\Infrastructure\Persistence\Doctrine\Repository;

use App\Modules\BuildermanCabinet\Domain\Entity\Builder;
use App\Modules\BuildermanCabinet\Domain\RepositoryInterface\BuilderRepositoryInterface;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BuilderRepository extends ServiceEntityRepository implements BuilderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    /**
     * @param int $id
     * @return Builder
     */
    public function findById(int $id): Builder
    {
        $userTable = $this->find($id);

        $builder = new Builder(
            $userTable->getId(),
            $userTable->getLogin(),
            $userTable->getFullName(),
        );
        return $builder;
    }
}