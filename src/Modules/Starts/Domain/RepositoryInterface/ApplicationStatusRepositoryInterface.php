<?php

namespace App\Modules\Starts\Domain\RepositoryInterface;

use App\Modules\Starts\Domain\Entity\ApplicationStatus;

interface ApplicationStatusRepositoryInterface
{
    /**
     * @return array<ApplicationStatus>
     */
    public function findAll(): array;
}