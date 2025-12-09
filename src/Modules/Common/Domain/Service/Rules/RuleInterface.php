<?php

namespace App\Modules\Common\Domain\Service\Rules;

interface RuleInterface
{
    public function check(object $context): void;
}