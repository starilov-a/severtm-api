<?php

namespace App\Modules\Starts\Domain\Context\Interfaces;

use App\Modules\Starts\Domain\Entity\ApplicationStatus;

interface HasApplicationStatusInterface
{
    public function getApplicationStatus(): ApplicationStatus;
}