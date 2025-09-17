<?php

namespace App\Modules\Common\CustomController;

use App\Modules\UserCabinet\Controllers\CustomController\UserSession;
use Symfony\Component\Config\Definition\Exception\Exception;

final class Auth
{
    public function login(array $data): void {
        if(UserSession::loggedIn()){
           throw new Exception();
        }
            Session::create(...$data);
    }

    public function logOut(): void
    {
        UserSession::logOut();
    }
}
