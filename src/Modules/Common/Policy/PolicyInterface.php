<?php

namespace App\Modules\UserCabinet\Domain\Policy;

interface PolicyInterface
{
    public function isAllowed(object $context): bool;
}