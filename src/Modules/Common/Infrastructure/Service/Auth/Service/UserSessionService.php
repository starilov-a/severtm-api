<?php

namespace App\Modules\Common\Infrastructure\Service\Auth\Service;

use App\Modules\Common\Infrastructure\Service\Auth\Entity\Session;
use Symfony\Component\HttpFoundation\RequestStack;


class UserSessionService
{
    static public function loggedIn(): bool
    {
        return $_SESSION['loggedIn'] ?? false;
    }

    static public function getUserId(): int
    {
        return $_SESSION['userId'] ?? false;
    }

    static public function getUserIp(): bool
    {
        return $_SESSION['UserIp'] ?? false;
    }

    static public function getUserAgent(): bool
    {
        return $_SESSION['UserAgent'] ?? false;
    }

    static public function getUserName(): bool
    {
        return $_SESSION['UserName'] ?? false;
    }

    static public function getUserPerms(): bool
    {
        return $_SESSION['perms'] ?? false;
    }

    static public function getPermsBuilder(): bool
    {
        return $_SESSION['permsBuilder'] ?? false;
    }

    static public function getDistrict(): bool
    {
        return $_SESSION['district'] ?? false;
    }

    static public function getRoles(): bool
    {
        return $_SESSION['roles'] ?? false;
    }
    static public function getSid(): string
    {
        return $_SESSION['sid'] ?? session_id();
    }
}
