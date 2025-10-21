<?php declare(strict_types=1);

namespace App\Modules\UserCabinet\Repository;

use App\Modules\UserCabinet\Entity\Tariff;
use App\Modules\UserCabinet\Entity\TariffGroup;
use App\Modules\UserCabinet\Entity\User;
use App\Modules\UserCabinet\Service\Dto\Request\TariffFilterDto\TariffFilterDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

class TariffRepository extends ServiceEntityRepository

{
    public const INTERNET_PRODUCT_SERVICE_CODE = 'internet';
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tariff::class);
    }

    public function getTariffs(\App\Modules\UserCabinet\Service\Dto\Request\TariffFilterDto $dto)
    {
        $qb = $this->createQueryBuilder('t');

//        if ($dto->getActiveStatus()) {
//            $qb->leftJoin('')
//        }

        $minPrice = $dto->getMinPrice();
        if ($minPrice) {
            $qb->andWhere('t.price > :minPrice')
                ->setParameter('minPrice', $minPrice);;
        }

        $qb->leftJoin('t.groups', 'tg');

        $codes = $dto->getGroupCodes();
        if (!empty($codes)) {
            foreach ($codes as $key => $code)
                $qb->andWhere('tg.code IN (:code'.$key.')')->setParameter('code'.$key, $code);
        }

        // может быть стоит убрать join я хз
        $regionCodes = $dto->getRegionGroupCodes();
        if (!empty($regionCodes)) {
            foreach ($regionCodes as $key => $code)
                $qb->andWhere('tg.code IN (:code'.$key.')')->setParameter('code'.$key, $code);
        }

        // Сортировка:
        $allowedOrder = ['t.price', 't.name'];
        $orderBy = in_array($dto->getOrderBy(), $allowedOrder, true) ? $dto->getOrderBy() : 't.price';
        $qb->orderBy($orderBy, $dto->getOrderDir());

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

    public function isAvailableForRegion(int $tariffId, int $regionId): bool
    {
        // TODO: сделать связь между группой и городом - в БД
        $groupsAndRegionIds = [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ];

        $sql = <<<SQL
        SELECT 1 FROM tariffs_belong_groups tbg
        JOIN tariffs_groups tg ON tg.tariffs_group_id = tbg.tariffs_group_id
        WHERE tbg.tc_id = :tid AND tg.tariffs_grp_code = :tgc
            LIMIT 1
        SQL;

        return false !== $this->getEntityManager()->getConnection()->fetchOne($sql, [
                'tid' => $tariffId,
                'tgc' => $groupsAndRegionIds[$regionId],
            ]);
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