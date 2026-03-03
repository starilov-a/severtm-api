<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\UserCabinet\Application\LkPaymentsService;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'ЛК / Платежи')]
class PaymentsController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[OA\Get(
        summary: 'Получить текущий баланс',
        description: 'Возвращает текущий баланс пользователя.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Баланс получен',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'balance', type: 'number', format: 'float', example: 125.50),
                    ]
                ),
            ]
        )
    )]
    #[Route('/get-balance', name: 'getBalance', methods: ['GET'])]
    public function getBalance(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getBalance($uid));
    }

    #[OA\Get(
        summary: 'Списания',
        description: <<<TXT
                        Возвращает список списаний (история начислений/списаний) пользователя.
                        Поддерживает пагинацию через query-параметры limit/offset.
                        TXT
    )]
    #[OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 50), description: 'Лимит записей')]
    #[OA\Parameter(name: 'offset', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 0), description: 'Смещение')]
    #[OA\Response(
        response: 200,
        description: 'Список списаний',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 101),
                            new OA\Property(property: 'date', type: 'string', example: '2026-01-29 12:00:00'),
                            new OA\Property(property: 'amount', type: 'number', format: 'float', example: 350.00),
                            new OA\Property(property: 'userServMode', type: 'string', example: 'Интернет 100 Мбит/с'),
                        ]
                    )
                ),
            ]
        )
    )]
    #[Route(
        '/get-write-offs',
        name: 'getWriteOffs',
        methods: ['GET']
    )]
    public function getWriteOffs(Request $request, LkPaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto();
        if ($request->query->get('limit'))
            $filterDto->setLimit($request->query->get('limit'));
        if ($request->query->get('offset'))
            $filterDto->setOffset($request->query->get('offset'));

        $uid = UserSessionService::getUserId();

        return $this->responseData($paymentsService->getWriteOffs($uid, $filterDto)->toArray());
    }

    #[OA\Get(
        summary: 'Пополнения',
        description: <<<TXT
                        Возвращает историю пополнений пользователя.
                        Поддерживает пагинацию через query-параметры limit/offset.
                        TXT
    )]
    #[OA\Parameter(name: 'limit', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 50), description: 'Лимит записей')]
    #[OA\Parameter(name: 'offset', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 0), description: 'Смещение')]
    #[OA\Response(
        response: 200,
        description: 'Список пополнений',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 555),
                            new OA\Property(property: 'login', type: 'string', nullable: true, example: 'user123'),
                            new OA\Property(property: 'additionalInformation', type: 'string', example: 'Терминал'),
                            new OA\Property(property: 'paymentType', type: 'string', example: 'Терминал'),
                            new OA\Property(property: 'comment', type: 'string', nullable: true, example: 'Оплата услуг'),
                            new OA\Property(property: 'amount', type: 'number', format: 'float', nullable: true, example: 1000.00),
                            new OA\Property(property: 'date', type: 'string', example: '2026-01-29 11:30:00'),
                        ]
                    )
                ),
            ]
        )
    )]
    #[Route(
        '/get-replenishments',
        name: 'getReplenishments',
        methods: ['GET']
    )]
    public function getReplenishments(Request $request, LkPaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto();

        if ($request->query->get('limit'))
            $filterDto->setLimit($request->query->get('limit'));
        if ($request->query->get('offset'))
            $filterDto->setOffset($request->query->get('offset'));

        $uid = UserSessionService::getUserId();

        return $this->responseData($paymentsService->getReplenishments($uid, $filterDto)->toArray());
    }

    #[OA\Get(
        summary: 'Задолженность',
        description: 'Возвращает текущую задолженность пользователя (если есть).'
    )]
    #[OA\Response(
        response: 200,
        description: 'Задолженность получена',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'debt', type: 'number', format: 'float', example: 500.00),
                    ]
                ),
            ]
        )
    )]
    #[Route(
        '/get-debt',
        name: 'getDebt',
        methods: ['GET']
    )]
    public function getDebt(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getDebt($uid));
    }

    #[OA\Get(
        summary: 'Ссылка на оплату',
        description: <<<TXT
                        Возвращает ссылку на страницу оплаты в зависимости от региона (district) текущего пользователя.
                        Если регион не определён — вернёт бизнес-ошибку.
                        TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Ссылка на оплату',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'data', type: 'string', example: 'https://izet.ru/oplata/'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Регион пользователя не определён',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Регион пользователя не определен'),
            ]
        )
    )]
    #[Route(
        '/get-payment-link',
        name: 'getPaymentLink',
        methods: ['GET']
    )]
    public function getPaymentLink(LkPaymentsService $paymentsService): JsonResponse
    {
        $link = $paymentsService->getPaymentLink(UserSessionService::getDistrict());
        return $this->responseData($link);
    }

    #[OA\Get(
        summary: 'Проверка возможности взять отсрочку',
        description: <<<TXT
                        Возвращает статус по отсрочке:
                        - isAvailable: можно ли взять новую отсрочку сейчас
                        - isActive: активна ли отсрочка сейчас
                        - count: сколько доступно отсрочек
                        - endDate: дата окончания активной отсрочки (если isActive=true)
                        TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Статус отсрочки',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'isAvailable', type: 'boolean', example: true),
                        new OA\Property(property: 'isActive', type: 'boolean', example: false),
                        new OA\Property(property: 'count', type: 'integer', example: 1),
                        new OA\Property(property: 'endDate', type: 'string', nullable: true, example: '2026-01-30'),
                    ]
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Текущая возможность взять отсрочку'),
            ]
        )
    )]
    #[Route(
        '/can-take-break',
        name: 'canTakeBreak',
        methods: ['GET']
    )]
    public function canTakeBreak(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        return $this->response($paymentsService->canTakeBreak($uid), 'Текущая возможность взять отсрочку');
    }

    #[OA\Post(
        summary: 'Активировать отсрочку на сутки',
        description: 'Запускает процесс выдачи отсрочки на 1 день. Операция выполняется в транзакции.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Отсрочка активирована',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Отсрочка на сутки успешно активирована!'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Бизнес-ошибка (например, отсрочка недоступна)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Отсрочка недоступна'),
            ]
        )
    )]
    #[Route(
        '/take-break',
        name: 'takeBreak',
        methods: ['POST']
    )]
    public function takeBreak(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();

        $paymentsService->takeBreak($uid);

        return $this->responseMessage('Отсрочка на сутки успешно активирована!');
    }
}
