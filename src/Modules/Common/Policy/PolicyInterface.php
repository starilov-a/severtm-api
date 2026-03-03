<?php

namespace App\Modules\Common\Policy;

interface PolicyInterface
{
    public function isAllowed(object $context): bool;
}