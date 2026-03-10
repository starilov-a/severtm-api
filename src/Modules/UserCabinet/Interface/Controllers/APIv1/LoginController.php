<?php

namespace App\Modules\UserCabinet\Interface\Controllers\APIv1;

use App\Modules\Common\Interface\Api\Controller;
use App\Modules\UserCabinet\Infrastructure\Exception\BusinessException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\Auth;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: "ЛК / Авторизация")]
class LoginController extends Controller
{
    public function authenticate(): bool
    {
        return false;
    }

    #[OA\Post(
        summary: 'Авторизация пользователя',
        description: <<<TXT
                    Принимает логин и пароль.
                    При успехе создаётся серверная сессия, клиент получает cookie PHPSESSID.
                    Дальнейшие запросы к защищённым эндпоинтам выполняются с этой cookie.
                    TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['login', 'password'],
            properties: [
                new OA\Property(property: 'login', type: 'string', example: 'user123'),
                new OA\Property(property: 'password', type: 'string', example: 'secret'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешная авторизация',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                    ]
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Пользователь авторизован'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики (например, не передан логин/пароль)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Не указан логин или пароль'),
            ]
        )
    )]
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

    #[OA\Post(
        summary: 'Выход из системы',
        description: 'Завершает текущую сессию пользователя (очищает/инвалидирует PHPSESSID на сервере).'
    )]
    #[OA\Response(
        response: 200,
        description: 'Успешный выход',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                    ]
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Пользователь вышел из системы'),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Не авторизован',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Не авторизован'),
            ]
        )
    )]
    #[Route('/logout', name: 'app_logout_post', methods: ['POST'], format: 'json')]
    public function logout(Request $request, Auth $auth): JsonResponse
    {
        $auth->logOut();
        return $this->response(['success' => true],'Пользователь вышел из системы');
    }
}
