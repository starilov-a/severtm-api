<?php

namespace App\Modules\Common\Domain\Policy;

interface PolicyInterface
{
    public function isAllowed(object $context): bool;
}