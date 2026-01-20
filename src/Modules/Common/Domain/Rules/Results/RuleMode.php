<?php

namespace App\Modules\Common\Domain\Rules\Results;

enum RuleMode: string
{
    case HARD = 'hard';
    case SOFT = 'soft';
}