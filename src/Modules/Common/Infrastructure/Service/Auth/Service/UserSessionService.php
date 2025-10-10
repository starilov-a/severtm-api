<?php

namespace App\Modules\Common\Infrastructure\Service\Auth\Service;

use App\Modules\Common\Infrastructure\Service\Auth\Entity\Session;
use Symfony\Component\HttpFoundation\RequestStack;


class UserSessionService
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

     public function checkAuth():bool {
        return $this->loggedIn();
    }

    // возможно после разлогирования будут производиться доп операции,
    // к примеру отметка времени, когда пользователь был в сети последний раз.
    // но пока это метод просто уничтожает сессию
    static public function logOut(\Symfony\Component\HttpFoundation\Session\Session $session): void {
        Session::destroy($session);
    }

    public function loggedIn(): bool{
        $session = $this->requestStack->getSession();
        return $session->get('loggedIn', false);
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
