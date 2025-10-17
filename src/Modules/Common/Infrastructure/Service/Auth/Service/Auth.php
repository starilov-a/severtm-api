<?php

namespace App\Modules\Common\Infrastructure\Service\Auth\Service;

use App\Modules\Common\Infrastructure\Service\Auth\Entity\Session;

final class Auth
{
    public function login(
         $dto
    ): void
    {
        if(UserSessionService::loggedIn()){
            throw new AuthException("Already logged in", 403);
        }
        Session::create($dto);
    }
}
