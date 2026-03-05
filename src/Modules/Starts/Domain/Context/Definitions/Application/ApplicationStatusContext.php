<?php

namespace App\Modules\Starts\Domain\Context\Definitions\Application;

use App\Modules\Starts\Domain\Context\Interfaces\HasApplicationStatusInterface;
use App\Modules\Starts\Domain\Entity\ApplicationStatus;

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