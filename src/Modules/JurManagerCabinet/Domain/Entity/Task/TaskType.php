<?php

declare(strict_types=1);

namespace App\Modules\JurManagerCabinet\Domain\Entity\Task;

final class TaskType
{
    public const CONTRACT_REISSUE = 'contract_reissue';

    public static function allowed(): array
    {
        return [self::CONTRACT_REISSUE];
    }

    private function __construct() {}
}
