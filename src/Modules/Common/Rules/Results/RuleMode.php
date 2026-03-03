<?php

namespace App\Modules\Common\Rules\Results;

enum RuleMode: string
{
    case HARD = 'hard';
    case SOFT = 'soft';
}