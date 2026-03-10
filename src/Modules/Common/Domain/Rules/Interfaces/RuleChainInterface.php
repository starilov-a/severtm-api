<?php

namespace App\Modules\Common\Domain\Rules\Interfaces;

use App\Modules\Common\Domain\Rules\Results\RuleResult;

interface RuleChainInterface
{
    // Проходят все проверки. Возвращаем RESULT
    public function checkAll(object $context): RuleResult;

    // Проходит хотя бы 1 проверка. Возвращаем RESULT
    public function checkAny(object $context): RuleResult;

}