<?php declare(strict_types=1);


namespace App\Modules\Common;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;


abstract class BaseRepository extends ServiceEntityRepository
{

}