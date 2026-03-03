<?php

namespace App\Modules\UserCabinet\Infrastructure\Service\Auth\Service;

use App\Modules\UserCabinet\Domain\Entity\WebUser;
use App\Modules\UserCabinet\Infrastructure\Exception\AuthException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Dto\SessionDto;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;

final class Auth
{
    protected EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function login(string $login, string $pass): void
    {

        $user = $this->em->getRepository(WebUser::class)->findOneBy(
            [
                'login' => $login,
                'passwd_hash' => md5($pass)
            ]
        );

        if (!$user) {
            throw new AuthException('Пользователь не найден', 403);
        }

        Session::create(new SessionDto(
            true,
            $user->getUid(),
            $user->getUser()->getFullName(),
            [], [],
            $user->getUser()->getDistrict(),
            []
        ));
    }

    // возможно после разлогирования будут производиться доп операции,
    // к примеру отметка времени, когда пользователь был в сети последний раз.
    // но пока это метод просто уничтожает сессию
    public function logOut(): void
    {
        Session::destroy();
    }

    public function checkAuth(): bool
    {
        return UserSessionService::loggedIn();
    }
}
