<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Exception\AuthException;
use App\Modules\Common\Infrastructure\Service\Auth\Dto\SessionDto;
use App\Modules\Common\Infrastructure\Service\Auth\Service\Auth;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Entity\WebUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Modules\Common\Infrastructure\Exception;

final class LoginController extends Controller
{
    public function authenticate(): bool
    {
        return false;
    }

    /**
     * @throws \Exception
     */
    #[Route('/login', name: 'app_login_post', methods: ['POST'], format: 'json')]
    public function login(Request $request, Auth $auth): JsonResponse
    {
        $auth->login($request->get('login'), $request->get('pass'));
        return $this->responseMessage('User login');
    }


    #[Route('/logout', name: 'app_logout_post', methods: ['POST'], format: 'json')]
    public function logout(Request $request, Auth $auth): JsonResponse
    {
        $auth->logOut();
        return $this->responseMessage('User logout');
    }
}
