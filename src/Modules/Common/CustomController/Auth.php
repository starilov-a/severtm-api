<?php

namespace App\Modules\Common\CustomController;

use App\Modules\UserCabinet\Controllers\CustomController\UserSession;
use App\Modules\UserCabinet\Entity\WebUser;
use App\Modules\UserCabinet\Service\Dto\Session\SessionDto;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Config\Definition\Exception\Exception;

final class Auth
{
    public function login(SessionDto $dto, \Symfony\Component\HttpFoundation\Session\Session $session): void
    {
        Session::create($dto, $session);
    }

}
