<?php

namespace App\Modules\BuildermanCabinet\Domain\Context\Interfaces;

use App\Modules\BuildermanCabinet\Domain\Entity\ApplicationStatus;

interface HasApplicationStatusInterface
{
    public function getApplicationStatus(): ApplicationStatus;
}