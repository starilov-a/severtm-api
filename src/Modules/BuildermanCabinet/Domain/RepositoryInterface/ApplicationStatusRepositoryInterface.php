<?php

namespace App\Modules\BuildermanCabinet\Domain\RepositoryInterface;

use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus;

interface ApplicationStatusRepositoryInterface
{
    /**
     * @return array<ApplicationStatus>
     */
    public function findAll(): array;
}