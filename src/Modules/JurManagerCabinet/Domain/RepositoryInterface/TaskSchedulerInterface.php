<?php

declare(strict_types=1);

namespace App\Modules\JurManagerCabinet\Domain\RepositoryInterface;

use App\Modules\JurManagerCabinet\Application\Dto\Request\Queue\ScheduleReissueTaskDto;
use App\Modules\JurManagerCabinet\Domain\Entity\Task\ScheduledTask;

interface TaskSchedulerInterface
{
    public function scheduleForReissue(ScheduleReissueTaskDto $dto): ScheduledTask;
}