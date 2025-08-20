<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;


use App\AppBundle\Core\Exception\DbCriticalException;
use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\User;
use App\Modules\UserCabinet\Service\Exception\UserNotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
