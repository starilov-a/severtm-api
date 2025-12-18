<?php

namespace App\Modules\Common\Domain\Service\Rules\Results;

enum RuleMode: string
{
    case HARD = 'hard';
    case SOFT = 'soft';
}