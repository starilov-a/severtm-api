<?php

namespace App\Modules\UserCabinet\Controllers\CustomController;

use App\Modules\UserCabinet\Controllers\CustomController\UserSession;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;


class Session extends AbstractController
{
    // реализуем SingleTone дабы избежать повторного создания сессии,
    // в один жзненный цикл скрипта.
    // стоит уделить внимание, как этот класс будет реализовавыться,
    // когда пользовательская сессия уже запущена
    // private static ?Session $instance = null;


    private bool $loggedIn;
    private ?int $userId;
    private ?string $userIp;
    private ?string $userAgent;
    private ?string $userName;
    private array $perms = [];
    private array $permsBuilder = [];
    private ?int $district;
    private ?array $roles = [];


    private function __construct(){}
    protected function __clone(){}

    static public function create(
        int    $userId,
        string $userName,
        array  $perms,
        array  $permsBuilder,
        int    $district,
        array  $roles
    ): void
    {
        if (!UserSession::loggedIn()) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $userId;
            $_SESSION['userName'] = $userName;
            $_SESSION['perms'] = $perms;
            $_SESSION['permsBuilder'] = $permsBuilder;
            $_SESSION['district'] = $district;
            $_SESSION['roles'] = $roles;
            $_SESSION['userIp'] = self::getUserIp();
            $_SESSION['userAgent'] = self::getUserAgent();
        }
    }

    static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    static public function getUserIp(): string
    {
        $Ip = $_SERVER['REMOTE_ADDR'];
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $Ip3)) {
                $Ip2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
                $Ip = preg_replace($Ip2, $Ip, $Ip3[1]);
            }
        }
        if ($Ip == "") {
            $Ip = "x.x.x.x";
        }
        return $Ip;
    }

    static public function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
}
