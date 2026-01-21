<?php declare(strict_types=1);

namespace App\Modules\Common\Domain\Repository;

use App\Modules\Common\Domain\Entity\Tariff;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Service\Dto\Request\TariffFilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class TariffRepository extends ServiceEntityRepository

{
    public const INTERNET_PRODUCT_SERVICE_CODE = 'internet';
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function getTariffs(\App\Modules\Common\Domain\Service\Dto\Request\TariffFilterDto $dto)
    {
        $qb = $this->createQueryBuilder('t');

        $qb->leftJoin('t.groups', 'tg');

        // 2. проверка наличия группы региона
        $regionCodes = $dto->getRegionGroupCodes();
        if (!empty($regionCodes)) {
            foreach ($regionCodes as $key => $code)
                $qb->orWhere('tg.code IN (:code'.$key.')')->setParameter('code'.$key, $code);
        }

        // Сортировка:
        $qb->orderBy('t.'.$dto->getOrderBy(), $dto->getOrderDir());

        return $qb->getQuery()->getResult();
    }

    public function getCurrentForUser(int $uid): ?Tariff
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('t')
            ->from(User::class, 'u')
            ->leftJoin('u.currentTariff', 't')
            ->andWhere('u.id = :uid')->setParameter('uid', $uid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getNextForUser(int $uid): ?Tariff
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        return $qb->select('t')
            ->from(User::class, 'u')
            ->leftJoin('u.nextTariff', 't')
            ->andWhere('u.id = :uid')->setParameter('uid', $uid)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function belongsToGroupCode(int $tariffId, string $groupCode): bool
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('1')
            ->from(Tariff::class, 't')
            ->join('t.groups', 'g')
            ->andWhere('t.id = :tid')->setParameter('tid', $tariffId)
            ->andWhere('g.code = :code')->setParameter('code', $groupCode)
            ->setMaxResults(1);

        return (bool) $qb->getQuery()->getOneOrNullResult();
    }

    /*
     * отчистить назначенные тарифы (чистка в user_serv_modes)
     * */
    public function clearAssignedTariffs(int $uid, int $fid): bool
    {
        $sql = <<<SQL
            DELETE um
            FROM user_serv_modes um
            JOIN prod_serv_modes m ON m.id = um.srvmode_id
            JOIN products_services s ON s.id = m.srv_id
            WHERE um.uid = :uid
            AND um.fid = :fid
            AND s.str_code = :internet
        SQL;

        // TODO: добавить лог
        $this->getEntityManager()->getConnection()->executeStatement($sql, [
            'uid' => $uid,
            'fid' => $fid,
            'internet' => self::INTERNET_PRODUCT_SERVICE_CODE,
        ]);

        return true;
    }

    public function setNextTariffForClient(int $fid, int $userId, int $tariffId): bool
    {
        $conn = $this->getEntityManager()->getConnection();

        // 1) найти srvmode_id для интернет-услуги у этого тарифа
        $srvmodeId = $conn->fetchOne(<<<SQL
            SELECT psm.id
            FROM tariffs_current t
            JOIN prod_serv_modes psm ON psm.id = t.srvmode_id
            JOIN products_services ps ON ps.id  = psm.srv_id
            WHERE t.id = :tid
              AND ps.str_code = :internet
            LIMIT 1
        SQL, [
            'tid'      => $tariffId,
            'internet' => self::INTERNET_PRODUCT_SERVICE_CODE,
        ], [
            'tid'      => ParameterType::INTEGER,
            'internet' => ParameterType::STRING,
        ]);

        if ($srvmodeId === false)
            throw new \Exception('Для выбранного тарифа не найден интернет-режим (srvmode_id).');

        // TODO: добавить лог

        // 2) вставка в user_serv_modes
        $conn->executeStatement(<<<SQL
            INSERT INTO user_serv_modes
                (fid, uid, srvmode_id, srvcost_id, ext, units, is_active, use_cost)
            VALUES
                (:fid, :uid, :srvmode, 1, NULL, 1, 1, 1)
        SQL, [
            'fid'     => $fid,
            'uid'     => $userId,
            'srvmode' => (int)$srvmodeId,
        ], [
            'fid'     => ParameterType::INTEGER,
            'uid'     => ParameterType::INTEGER,
            'srvmode' => ParameterType::INTEGER,
        ]);

        return true;
    }
}
