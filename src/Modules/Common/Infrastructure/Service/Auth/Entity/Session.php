<?php

namespace App\Modules\Common\Infrastructure\Service\Auth\Entity;

use App\Modules\Common\Infrastructure\Service\Auth\Dto\SessionDto;

final class Session
{
    // ��������� SingleTone ���� �������� ���������� �������� ������,
    // � ���� �������� ���� �������.
    // ����� ������� ��������, ��� ���� ����� ����� ���������������,
    // ����� ���������������� ������ ��� ��������
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
    private ?int $sid;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    static public function create(
        SessionDto $dto
    ): void
    {
        $_SESSION['loggedIn']=$dto->isLoggedIn();
        $_SESSION['userId'] =$dto->getUserId();
        $_SESSION['userName']= $dto->getUserName();
        $_SESSION['perms'] =$dto->getPerms();
        $_SESSION['permsBuilder'] =$dto->getPermsBuilder();
        $_SESSION['district'] =$dto->getDistrict();
        $_SESSION['roles'] =$dto->getRoles();
        $_SESSION['userIp'] =self::getUserIp();
        $_SESSION['userAgent'] =self::getUserAgent();
        $_SESSION['sid'] = session_id();
    }

    static function destroy(): void
    {
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
