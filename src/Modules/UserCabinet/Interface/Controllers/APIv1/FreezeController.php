<?php

namespace App\Modules\UserCabinet\Interface\Controllers\APIv1;

use App\Modules\Common\Interface\Api\Controller;
use App\Modules\UserCabinet\Application\Dto\Validator\EnableFreezeValidatorDto;
use App\Modules\UserCabinet\Application\UseCase\Freeze\FreezeProfileUseCase;
use App\Modules\UserCabinet\Application\UseCase\Freeze\GetFreezeStatusUseCase;
use App\Modules\UserCabinet\Application\UseCase\Freeze\GetReasonForFreezeUseCase;
use App\Modules\UserCabinet\Application\UseCase\Freeze\UnfreezeProfileUseCase;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'ЛК / Заморозка')]
class FreezeController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[OA\Get(
        summary: 'Список причин заморозки',
        description: 'Возвращает список причин, доступных клиенту для заморозки аккаунта.'
    )]
    #[OA\Response(
        response: 200,
        description: 'Список причин',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Отпуск'),
                        ]
                    )
                ),
                new OA\Property(property: 'message', type: 'string', example: 'Список доступных причин заморозки'),
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
        '/get-reason-for-freeze',
        name: 'getReasonForFreeze',
        methods: ['GET']
    )]
    public function getReasonForFreeze(GetReasonForFreezeUseCase $useCase)
    {
        return $this->response($useCase->handle(), 'Список доступных причин заморозки');
    }

    #[OA\Post(
        summary: 'Запрос на заморозку',
        description: <<<TXT
                        Создаёт задачу на заморозку профиля с указанной даты.
                        
                        Валидация:
                        - startDate обязателен и должен быть в формате ДД.ММ.ГГГГ
                        - startDate должен быть строго в будущем (после сегодняшнего дня)
                        - reason_id обязателен
                        TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['startDate', 'reason_id'],
            properties: [
                new OA\Property(
                    property: 'startDate',
                    type: 'string',
                    example: '31.01.2026',
                    description: 'Дата начала заморозки в формате ДД.ММ.ГГГГ'
                ),
                new OA\Property(property: 'reason_id', type: 'integer', example: 2, description: 'ID причины заморозки'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Запрос принят',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Аккаунт будет заморожен с указанного числа!'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики/валидации',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Ошибка бизнес-логики'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    nullable: true,
                    example: ['startDate' => 'Дата должна быть в формате ДД.ММ.ГГГГ']
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
        '/enable-freeze',
        name: 'enableFreeze',
        methods: ['POST']
    )]
    public function enableFreeze(Request $request, FreezeProfileUseCase $useCase): JsonResponse
    {
        $data = !empty($request->getContent()) ? $request->toArray() : [];
        $this->validate(new EnableFreezeValidatorDto(), $data);

        $uid = UserSessionService::getUserId();

        $useCase->handle($uid, $data['startDate'], $data['reason_id']);

        return $this->responseMessage('Аккаунт будет заморожен с указанного числа!');
    }

    #[OA\Post(
        summary: 'Запрос на заморозку',
        description: <<<TXT
                        Создаёт задачу на заморозку профиля с указанной даты.
                        
                        Валидация:
                        - startDate обязателен и должен быть в формате ДД.ММ.ГГГГ
                        - startDate должен быть строго в будущем (после сегодняшнего дня)
                        - reason_id обязателен
                        TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['startDate', 'reason_id'],
            properties: [
                new OA\Property(
                    property: 'startDate',
                    type: 'string',
                    example: '31.01.2026',
                    description: 'Дата начала заморозки в формате ДД.ММ.ГГГГ'
                ),
                new OA\Property(property: 'reason_id', type: 'integer', example: 2, description: 'ID причины заморозки'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Запрос принят',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Аккаунт будет заморожен с указанного числа!'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики/валидации',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Ошибка бизнес-логики'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    nullable: true,
                    example: ['startDate' => 'Дата должна быть в формате ДД.ММ.ГГГГ']
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
        '/disable-freeze',
        name: 'disableFreeze',
        methods: ['POST']
    )]
    public function disableFreeze(UnfreezeProfileUseCase $useCase): JsonResponse
    {
        $uid = UserSessionService::getUserId();

        $useCase->handle($uid);

        return $this->responseMessage('Аккаунт разморожен!');
    }

    #[OA\Post(
        summary: 'Разморозить аккаунт',
        description: 'Отменяет заморозку/выполняет разморозку пользователя (если доступно по правилам).'
    )]
    #[OA\Response(
        response: 200,
        description: 'Разморожено',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Аккаунт разморожен!'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики (например, разморозка недоступна)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Разморозка недоступна'),
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
        '/get-status-freeze',
        name: 'getStatusFreeze',
        methods: ['GET']
    )]
    public function getStatusFreeze(GetFreezeStatusUseCase $useCase): JsonResponse
    {
        $uid = UserSessionService::getUserId();

        return $this->response($useCase->handle($uid), 'Информация о заморозке');
    }
}
