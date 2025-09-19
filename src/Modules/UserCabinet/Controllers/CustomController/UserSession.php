<?php

namespace App\Modules\UserCabinet\Controllers\CustomController;

use App\Modules\Common\CustomController\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserSession
{

    static public function checkAuth():bool {
        return self::loggedIn();
    }

    // возможно после разлогирования будут производиться доп операции,
    // к примеру отметка времени, когда пользователь был в сети последний раз.
    // но пока это метод просто уничтожает сессию
    static public function logOut(): void {
        Session::destroy();
    }

    static public function loggedIn(): bool{
        return $_SESSION['loggedIn'] ?? false;
    }

    static public function getUserId(): bool{
        return $_SESSION['userId'] ?? false;
    }

    static public function getUserIp(): bool{
        return $_SESSION['UserIp'] ?? false;
    }

    static public function getUserAgent(): bool{
        return $_SESSION['UserAgent'] ?? false;
    }

    static public function getUserName(): bool {
        return $_SESSION['UserName'] ?? false;
    }

    static public function getUserPerms(): bool{
        return $_SESSION['perms'] ?? false;
    }

    static public function getPermsBuilder(): bool{
        return $_SESSION['permsBuilder'] ?? false;
    }

    static public function getDistrict(): bool{
        return $_SESSION['district'] ?? false;
    }

    static public function getRoles(): bool{
        return $_SESSION['roles'] ?? false;
    }
}
