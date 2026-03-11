<?php

namespace App\Modules\BuildermanCabinet\Domain\Context\Definitions\Application;

use App\Modules\BuildermanCabinet\Domain\Context\Interfaces\HasApplicationStatusInterface;
use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus;

class ApplicationStatusContext implements HasApplicationStatusInterface
{
    public function __construct(
        protected ApplicationStatus $applicationStatus
    ) {}

    public function getApplicationStatus(): ApplicationStatus
    {
        return $this->applicationStatus;
    }
}