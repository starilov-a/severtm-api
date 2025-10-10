<?php

namespace App\Modules\Common\Infrastructure\Exception;

class UserNotFoundException extends \App\Modules\Common\Infrastructure\Exception\BusinessException
{
    public function __construct(int $uid)
    {
        parent::__construct(
            sprintf('Пользователь с uid - %d не найден', $uid),
            'USER_NOT_FOUND'
        );
    }
}