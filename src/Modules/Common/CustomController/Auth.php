<?php

namespace App\Modules\Common\CustomController;

use App\Modules\Common\Infrastructure\Service\Auth\Entity\Session;
use App\Modules\UserCabinet\Service\Dto\Session\SessionDto;

final class Auth
{
    public function login(SessionDto $dto, \Symfony\Component\HttpFoundation\Session\Session $session): void
    {
        Session::create($dto, $session);
    }

}
