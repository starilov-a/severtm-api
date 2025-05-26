<?php declare(strict_types=1);


namespace App\Modules\Common;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;


abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * @param int|null $page
     * @param int|null $per_page
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @param ParameterBag|null $params
     * @param int|null $limit
     * @return int|mixed|string
     */
    public function findAllPaged(
        ?int $page = null,
        ?int $per_page = null,
        ?string $sortField = null,
        ?string $sortDirection = 'ASC',
        ?ParameterBag $params = null,
        ?int $limit = null
    )
    {
        $qb = $this->createQueryBuilder('a');
        if ($page && $per_page) {
            $qb->setFirstResult(($page - 1) * $per_page)
                ->setMaxResults($per_page);
        }

        if ($sortField) {
            if (!strstr($sortField, '.')) {
                $sortField = 'a.' . $sortField;
            }
            $qb->orderBy($sortField, $sortDirection);
        }

        if($limit){
            $qb->setMaxResults($limit);
        }

        $this->addCriteria($qb, $params);

        return $qb->getQuery()->execute();
    }

    /**
     * @param ParameterBag $params
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findAllCount(ParameterBag $params) :int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(1)');

        $this->addCriteria($qb, $params);

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    abstract protected function addCriteria(QueryBuilder $qb, ParameterBag $params);
}