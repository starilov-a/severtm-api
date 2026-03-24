<?php

namespace App\Modules\JurManagerCabinet\Domain\Entity\Contract;

final class ContractStatus
{
    public const UNBLOCKED = 'unblocked';
    public const ON_REISSUED = 'on_reissued';
    public const BLOCKED = 'blocked';
    public const FROZEN = 'frozen';

    private function __construct() {}
}
