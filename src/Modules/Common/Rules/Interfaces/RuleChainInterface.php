<?php

namespace App\Modules\UserCabinet\Domain\Rules\Interfaces;

use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;

interface RuleChainInterface
{
    // Проходят все проверки. Возвращаем BOOLEAN
    public function checkAll(object $context): bool;

    // Проходят все проверки. Возвращаем RESULT
    public function checkAllWithResult(object $context): RuleResult;

    // Проходит хотя бы 1 проверка. Возвращаем BOOLEAN
    public function checkAny(object $context): bool;

    // Проходит хотя бы 1 проверка. Возвращаем RESULT
    public function checkAnyWithResult(object $context): RuleResult;

}