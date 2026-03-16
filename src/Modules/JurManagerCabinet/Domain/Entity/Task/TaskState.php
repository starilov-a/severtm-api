<?php

declare(strict_types=1);

namespace App\Modules\JurManagerCabinet\Domain\Entity\Task;

final class TaskState
{
    public const NEW = 'new';
    public const FINISHED = 'finished';
    public const ERROR = 'error';
    public const CANCELLED = 'cancelled';
    public const DEFERRED = 'deferred';

    private function __construct() {}
}
