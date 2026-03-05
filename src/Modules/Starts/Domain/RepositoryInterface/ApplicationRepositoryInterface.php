<?php

namespace App\Modules\Starts\Domain\RepositoryInterface;


use App\Modules\Starts\Domain\Entity\Application;
use App\Modules\Starts\Domain\Entity\ApplicationStatus;
use App\Modules\Starts\Domain\Entity\Builder;

interface ApplicationRepositoryInterface
{
    /**
     * @param Builder $builder
     * @param array<ApplicationStatus> $statuses
     * @return array<Application>
     */
    public function findListByBuilder(Builder $builder, array $statuses): array;
}