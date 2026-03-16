<?php

declare(strict_types=1);

namespace App\Modules\JurManagerCabinet\Domain\Entity\Reissue;

/**
 * Статусы процесса переоформления
 */
final class ContractReissueStatus
{
    public const SCHEDULED = 'scheduled';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';

    private function __construct() {}
}
