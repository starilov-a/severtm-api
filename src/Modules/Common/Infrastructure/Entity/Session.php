<?php

namespace App\Modules\Common\Infrastructure\Entity;

use App\Modules\UserCabinet\Service\Dto\Session\SessionDto;
use Symfony\Component\HttpFoundation\RequestStack;

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

    private RequestStack $requestStack;

    private function __construct()
    {
    }

    protected function __clone()
    {
    }

    static public function create(
        SessionDto                                        $dto,
        \Symfony\Component\HttpFoundation\Session\Session $session
    ): void
    {
        $session->set('loggedIn', $dto->isLoggedIn());
        $session->set('userId', $dto->getUserId());
        $session->set('userName', $dto->getUserName());
        $session->set('perms', $dto->getPerms());
        $session->set('permsBuilder', $dto->getPermsBuilder());
        $session->set('district', $dto->getDistrict());
        $session->set('roles', $dto->getRoles());
        $session->set('userIp', self::getUserIp());
        $session->set('userAgent', self::getUserAgent());
    }

    static function destroy(\Symfony\Component\HttpFoundation\Session\Session $session): void
    {
        $session->clear();
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
