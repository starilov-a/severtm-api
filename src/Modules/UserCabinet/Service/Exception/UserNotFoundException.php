<?php

namespace App\Modules\UserCabinet\Service\Exception;

use App\Modules\UserCabinet\Service\Exception\BusinessException;

class UserNotFoundException extends BusinessException
{
    public function __construct(int $uid)
    {
        parent::__construct(
            sprintf('Пользователь с uid - %d не найден', $uid),
            'USER_NOT_FOUND'
        );
    }
}