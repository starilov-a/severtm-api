<?php

namespace App\Modules\UserCabinet\Interface\Controllers\APIv1;

use App\Modules\UserCabinet\Application\LkClientTariffService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'ЛК / Тарифы')]
class TariffController extends Controller
{

    public function authenticate(): bool
    {
        return true;
    }

    #[OA\Get(
        summary: 'Текущий тариф',
        description: <<<TXT
Возвращает текущий активный тариф пользователя.

Используется для отображения:
- названия тарифа
- стоимости
- скорости/характеристик
TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Текущий тариф пользователя',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 5),
                        new OA\Property(property: 'name', type: 'string', example: 'Домашний 100'),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 650.00),
                        new OA\Property(property: 'speed', type: 'integer', example: 100),
                        new OA\Property(property: 'code', type: 'string', example: 'home_100'),
                    ]
                ),
            ]
        )
    )]
    #[Route(
        '/get-current-tariff',
        name: 'getCurrentTariff',
        methods: ['GET']
    )]
    public function getCurrentTariff(Request $request, LkClientTariffService $tariffService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $responseDto = $tariffService->getCurrentTariff($uid);
        return $this->responseData($responseDto->toArray());
    }

    #[OA\Post(
        summary: 'Запланировать смену тарифа',
        description: <<<TXT
Создаёт задачу на смену тарифа.

Важно:
- смена тарифа применяется не мгновенно, а согласно бизнес-правилам (например, с начала следующего периода)
- повторная смена может быть запрещена
TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['tariff_id'],
            properties: [
                new OA\Property(property: 'tariff_id', type: 'integer', example: 6, description: 'ID выбранного тарифа'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Запрос на смену тарифа создан',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Задача на смену тарифа успешно создана'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики (например, тариф недоступен)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Тариф недоступен для смены'),
            ]
        )
    )]
    #[Route(
        '/change-next-tariff',
        name: 'changeNextTariff',
        methods: ['POST'],
    )]
    public function changeNextTariff(Request $request, LkClientTariffService $tariffService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $data = $request->toArray();
        $tariffId = $data['tariff_id'];

        $tariffService->changeNextTariff($uid, $tariffId);

        return $this->response(true, 'Тариф на следующий месяц успешно изменен');
    }

    #[OA\Get(
        summary: 'Доступные тарифы',
        description: <<<TXT
Возвращает список тарифов, доступных пользователю для смены.

Список формируется на основе:
- текущего тарифа
- региона
- бизнес-ограничений (договор, услуги, заморозка и т.п.)
TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Список доступных тарифов',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 6),
                            new OA\Property(property: 'name', type: 'string', example: 'Домашний 300'),
                            new OA\Property(property: 'price', type: 'number', format: 'float', example: 850.00),
                            new OA\Property(property: 'speed', type: 'integer', example: 300),
                            new OA\Property(property: 'code', type: 'string', example: 'home_300'),
                        ]
                    )
                ),
            ]
        )
    )]
    #[Route(
        '/get-available-tariffs',
        name: 'getAvailableTariffs',
        methods: ['GET']
    )]
    public function getAvailableTariffs(LkClientTariffService $tariffService): JsonResponse
    {
        $uid = UserSessionService::getUserId();

        return $this->json([
            'data' => $tariffService->getAvailableTariffs($uid),
            'message' => 'Список доступных тарифов'
        ]);
    }
}
