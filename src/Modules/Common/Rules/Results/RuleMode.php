<?php

namespace App\Modules\UserCabinet\Domain\Rules\Results;

enum RuleMode: string
{
    case HARD = 'hard';
    case SOFT = 'soft';
}