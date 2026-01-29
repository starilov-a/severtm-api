<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkClientServService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class ServServiceController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[OA\Get(
        summary: 'Доступные услуги',
        description: <<<TXT
                        Возвращает список услуг и доступных режимов (modes), которые пользователь может подключить.
                        mode_id из этого списка используется в методе "Подключить услугу".
                        TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Список доступных услуг',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 10),
                            new OA\Property(property: 'name', type: 'string', example: 'Интернет'),
                            new OA\Property(property: 'code', type: 'string', example: 'internet'),
                            new OA\Property(
                                property: 'modes',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 101, description: 'ProdServMode ID'),
                                        new OA\Property(property: 'name', type: 'string', example: '100 Мбит/с'),
                                        new OA\Property(property: 'code', type: 'string', example: 'internet_100'),
                                    ]
                                )
                            ),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Не авторизован',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', example: 'Не авторизован')]
        )
    )]
    #[Route(
        '/get-available-servs',
        name: 'getAvailableServs',
        methods: ['GET']
    )]
    public function getAvailableServs(LkClientServService $servService): JsonResponse
    {
        $data = $servService->listAvailableServices();

        return $this->responseData($data);
    }

    #[OA\Get(
        summary: 'Текущие подключённые услуги',
        description: <<<TXT
                        Возвращает текущие услуги пользователя и активные пользовательские режимы (UserServMode).
                        Для отключения услуги используется usmid (это ID привязки UserServMode), передаваемый как mode_id в метод "Отключить услугу".
                        TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Список текущих услуг',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 10),
                            new OA\Property(property: 'name', type: 'string', example: 'Интернет'),
                            new OA\Property(property: 'code', type: 'string', example: 'internet'),
                            new OA\Property(property: 'type', type: 'string', example: 'ProductService'),
                            new OA\Property(
                                property: 'modes',
                                type: 'array',
                                items: new OA\Items(
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'usmid', type: 'integer', example: 5001, description: 'UserServMode ID'),
                                        new OA\Property(property: 'name', type: 'string', example: '100 Мбит/с'),
                                        new OA\Property(property: 'code', type: 'string', example: 'internet_100'),
                                        new OA\Property(property: 'type', type: 'string', example: 'UserServMode'),
                                    ]
                                )
                            ),
                        ]
                    )
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Не авторизован',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', example: 'Не авторизован')]
        )
    )]
    #[Route(
        '/get-current-servs',
        name: 'getCurrentServs',
        methods: ['GET']
    )]
    public function getCurrentServs(LkClientServService $servService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $data = $servService->getCurrentServices($uid);

        return $this->responseData($data);
    }

    #[OA\Post(
        summary: 'Отключить услугу',
        description: <<<TXT
Отключает услугу пользователя.

Важно:
- mode_id здесь — это НЕ ProdServMode, а ID привязки UserServMode (поле usmid из эндпоинта /get-current-servs).
TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['mode_id'],
            properties: [
                new OA\Property(property: 'mode_id', type: 'integer', example: 5001, description: 'UserServMode ID (usmid)'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Услуга отключена',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'data', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Услуга отключена.'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики (например, услуга не принадлежит договору пользователя)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Эта услуга не привязана к вашему договору.'),
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Не авторизован',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', example: 'Не авторизован')]
        )
    )]
    #[Route(
        '/disable-serv',
        name: 'disableServ',
        methods: ['POST']
    )]
    public function disableServ(Request $request, LkClientServService $servService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $data = $request->toArray();

        $servService->disableService($uid, $data['mode_id']);

        return $this->response(
            true,
            'Услуга отключена.'
        );
    }
}
