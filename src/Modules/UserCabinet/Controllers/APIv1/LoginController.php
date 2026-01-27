<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends Controller
{
    public function authenticate(): bool
    {
        return false;
    }

    /**
     * @throws \Exception
     */
    #[Route(
        '/login',
        name: 'app_login_post',
        methods: ['POST'],
        format: 'json'
    )]
    public function login(Request $request, Auth $auth): JsonResponse
    {
        $data = $request->toArray();
        if (empty($data['login']) || empty($data['password']))
            throw new BusinessException('Не указан логин или пароль');

        $auth->login($data['login'], $data['password']);
        return $this->response(['success' => true],'Пользователь авторизован' );
    }


    #[Route('/logout', name: 'app_logout_post', methods: ['POST'], format: 'json')]
    public function logout(Request $request, Auth $auth): JsonResponse
    {
        $auth->logOut();
        return $this->response(['success' => true],'Пользователь вышел из системы');
    }
}
