<?php

namespace App\Modules\Common\Infrastructure\Service\Auth\Service;

use App\Modules\Common\Infrastructure\Service\Auth\Entity\Session;

final class Auth
{
    public function login(
         $dto
    ): void
    {
        Session::create($dto);
    }
}
