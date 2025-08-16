<?php

namespace App\Modules\UserCabinet\Controllers\CustomController;

class Auth
{
    public function login(): void {
        if(!UserSession::loggedIn()){
            // ... проверка данных от пользователя

            $result = [];
            Session::create(...$result);
        }
        else{
            // Если пользователь авторизован выдаем ошибку
        }
    }

    public function logOut(): void
    {
        UserSession::logOut();
    }
}
