<?php

namespace App\Modules\BuildermanCabinet\Domain\RepositoryInterface;


use App\Modules\BuildermanCabinet\Application\Dto\Response\ApplicationListItemDto;
use App\Modules\BuildermanCabinet\Domain\Entity\Application;
use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus;
use App\Modules\BuildermanCabinet\Domain\Entity\Builder;

interface ApplicationRepositoryInterface
{
    /**
     * @param Builder $builder
     * @param ApplicationStatus[] $statuses
     * @return ApplicationListItemDto[] array
     */
    public function findActiveAppListByBuilder(Builder $builder, array $statuses): array;
}