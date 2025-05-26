<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository\User;


use App\Modules\Common\BaseRepository;
use App\Modules\UserCabinet\Entity\User\User;
use App\AppBundle\Core\Exception\DbCriticalException;
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

    /**
     * @throws DbCriticalException
     */
    public function findBuilders(): array
    {
        try {
            $query = "select u.user_id, u.user_name, d.district_name from users u
                                                left join user_role ur on ur.user_id = u.user_id
                                                left join roles r on r.id_role = ur.role_id
                                                join districts d on d.district_id = u.user_district 
                                                where u.user_perms in ('builder')
                                                and u.user_status = 'working' 
                                                group by user_id
                                                order by u.user_name";

            $connection = $this->getEntityManager()->getConnection();
            $stmt = $connection->prepare($query);
            return $stmt->executeQuery()->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Exception | \Doctrine\DBAL\Driver\Exception $exception) {
            throw new DbCriticalException($exception->getMessage());
        }
    }

    /**
     * @throws DbCriticalException
     */
    public function findBuildersByRegion(int $regionId): array
    {
        try {
            $query = "select u.user_id, u.user_name, d.district_name from users u
                                                left join user_role ur on ur.user_id = u.user_id
                                                left join roles r on r.id_role = ur.role_id
                                                join districts d on d.district_id = u.user_district
                                                where u.user_perms in ('builder')
                                                and u.user_status = 'working' 
                                                and d.region_id = ?
                                                group by user_id
                                                order by u.user_name";

            $connection = $this->getEntityManager()->getConnection();
            $stmt = $connection->prepare($query);
            $stmt->bindParam(1, $regionId);
            return $stmt->executeQuery()->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Exception | \Doctrine\DBAL\Driver\Exception $exception) {
            throw new DbCriticalException($exception->getMessage());
        }
    }

    /**
     * @throws DbCriticalException
     */
    public function findBuildersByDistrict(int $districtId): array
    {
        try {
            $query = "select u.user_id, u.user_name, d.district_name from users u
                                                left join user_role ur on ur.user_id = u.user_id
                                                left join roles r on r.id_role = ur.role_id
                                                join districts d on d.district_id = u.user_district
                                                where u.user_perms in ('builder')
                                                and u.user_status = 'working' 
                                                and d.district_id = ?
                                                group by user_id
                                                order by u.user_name";

            $connection = $this->getEntityManager()->getConnection();
            $stmt = $connection->prepare($query);
            $stmt->bindParam(1, $districtId);
            return $stmt->executeQuery()->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Exception | \Doctrine\DBAL\Driver\Exception $exception) {
            throw new DbCriticalException($exception->getMessage());
        }
    }

    protected function addCriteria(QueryBuilder $qb, ParameterBag $params)
    {
        echo __METHOD__;
    }

    private function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }

    /**
     * @throws DbCriticalException
     */
    public function findCablemans(): array
    {
        try {
            $query = "select u.user_id, u.user_name, d.district_name from users u
                                                left join user_role ur on ur.user_id = u.user_id
                                                left join roles r on r.id_role = ur.role_id
                                                join districts d on d.district_id = u.user_district
                                                where (u.user_perms in ('cableman') or (r.cid_role = 'role_is_juridical_users' and u.user_perms = 'manager'))
                                                and u.user_status = 'working' 
                                                group by user_id
                                                order by u.user_name";

            $connection = $this->getEntityManager()->getConnection();
            $stmt = $connection->prepare($query);
            return $stmt->executeQuery()->fetchAllAssociative();
        } catch (\Doctrine\DBAL\Exception | \Doctrine\DBAL\Driver\Exception $exception) {
            throw new DbCriticalException($exception->getMessage());
        }
    }

    /**
     * @throws DbCriticalException
     */
    public function getManagerRegion($userId): array
    {
        try {
            $connection = $this->getEntityManager()->getConnection()->getWrappedConnection();
            $stmt = $connection->prepare("select param_value from user_parameters where param_code = 'manager_region' and user_id = ? limit 1");
            $stmt->bindParam(1, $userId);
            $stmt->execute();
            return $stmt->fetchAll();
        }catch (Exception $exception){
            throw new DbCriticalException($exception->getMessage());
        }
    }

    /**
     * @throws DbCriticalException
     */
    public function updateCableAndAdapters(int $cable, int $adapter, int $builder): int
    {
        try {
            $connection = $this->getConnection();
            $stmt = $connection->prepare('CALL p_update_users_cable_adapters(@res, ?, ?, ?)');
            $stmt->bindParam(1, $cable);
            $stmt->bindParam(2, $adapter);
            $stmt->bindParam(3, $builder);
            $stmt->executeQuery();
            $stmt->free();
            $result = $connection->fetchAssociative("SELECT @res");
            return (int)($result['@res']);
        } catch (\Doctrine\DBAL\Driver\Exception | \Doctrine\DBAL\Exception $exception) {
            throw new DbCriticalException($exception->getMessage());
        }
    }
}
