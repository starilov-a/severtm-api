<?php

namespace App\Modules\Common\Domain\Service\Rules;

interface RuleChainInterface
{
    public function checkAll(RuleInterface $context): bool;
}