<?php

namespace App\Modules\UserCabinet\Adapter\Controllers\APIv1;

use App\Modules\Common\Adapter\Api\Controller;
use App\Modules\Common\Infrastructure\Exception\ValidationException;
use App\Modules\UserCabinet\Application\Dto\Validator\PasswordValidatorDto;
use App\Modules\UserCabinet\Application\Dto\Validator\WebUserValidatorDto;
use App\Modules\UserCabinet\Application\UseCase\UserProfile\CheckUserPasswordUseCase;
use App\Modules\UserCabinet\Application\UseCase\UserProfile\GetFullUserInfoUseCase;
use App\Modules\UserCabinet\Application\UseCase\UserProfile\GetShortUserInfoUseCase;
use App\Modules\UserCabinet\Application\UseCase\UserProfile\UpdateUserInfoUseCase;
use App\Modules\UserCabinet\Application\UseCase\UserProfile\UpdateUserPasswordUseCase;
use App\Modules\UserCabinet\Domain\Dto\Request\WebUserDto as WebUserRequestDto;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\UserSessionService;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'ЛК / Профиль')]
class UserProfileController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[OA\Get(
        summary: 'Краткая информация о пользователе',
        description: <<<TXT
Возвращает краткую информацию о пользователе:
- ФИО
- телефон
- email
- адрес подключения
TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Информация о пользователе',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'fullName', type: 'string', example: 'Иванов Иван Иванович'),
                        new OA\Property(property: 'phone', type: 'string', example: '+7 999 123-45-67'),
                        new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                        new OA\Property(property: 'address', type: 'string', example: 'г. Санкт-Петербург, Невский пр., 1'),
                    ]
                ),
            ]
        )
    )]
    #[Route(
        '/get-short-user-info',
        name: 'getShortUserInfo',
        methods: ['GET']
    )]
    public function getShortUserInfo(GetShortUserInfoUseCase $useCase): JsonResponse
    {
        $dtoResponse = $useCase->handle(UserSessionService::getUserId());
        return $this->responseData($dtoResponse);
    }

    #[OA\Get(
        summary: 'Полная информация о пользователе',
        description: <<<TXT
Возвращает полную информацию о пользователе:
TXT
    )]
    #[OA\Response(
        response: 200,
        description: 'Информация о пользователе'
    )]
    #[Route(
        '/get-full-user-info',
        name: 'getFullUserInfo',
        methods: ['GET']
    )]
    public function getFullUserInfo(GetFullUserInfoUseCase $useCase): JsonResponse
    {
        $dtoResponse = $useCase->handle(UserSessionService::getUserId());
        return $this->responseData($dtoResponse);
    }

    #[OA\Post(
        summary: 'Обновить данные профиля',
        description: <<<TXT
Обновляет контактные данные пользователя.

Можно изменять:
- телефон
- email
- ФИО

Адрес и договорные данные через данный метод не изменяются.
TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'fullName', type: 'string', example: 'Иванов Иван Иванович'),
                new OA\Property(property: 'phone', type: 'string', example: '+7 999 123-45-67'),
                new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Профиль обновлён',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Данные пользователя обновлены'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка валидации или бизнес-логики',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Некорректный формат email'),
                new OA\Property(property: 'errors', type: 'object', nullable: true),
            ]
        )
    )]
    #[Route(
        '/update-user-info',
        name: 'updateUserInfo',
        methods: ['POST']
    )]
    public function updateUserInfo(Request $request, UpdateUserInfoUseCase $useCase, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->toArray();
        $allowFields = ['comment', 'phone', 'email'];

        $webUser = new webUserValidatorDto();
        foreach ($allowFields as $field) {
            $webUser->{$field} = $data[$field];
        }

        if (count($errors = $validator->validate($webUser)) > 0) {
            $errorsString = [];
            foreach ($errors as $error) {
                $errorsString[] = $error->getMessage();
            }
            return $this->response(
                $errorsString,
                implode(", ", $errorsString),
                400
            );
        }

        $uid = UserSessionService::getUserId();
        $data['id'] = $uid;

        $webUseDto = new WebUserRequestDto(...$data);
        return $this->response($useCase->handle($uid, $webUseDto), 'Пользовательская информация обновлена');
    }

    #[OA\Post(
        summary: 'Сменить пароль',
        description: <<<TXT
Позволяет пользователю изменить пароль от личного кабинета.

Важно:
- старый пароль должен быть корректным
- новый пароль должен соответствовать требованиям безопасности
TXT
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['oldPassword', 'newPassword'],
            properties: [
                new OA\Property(property: 'oldPassword', type: 'string', example: 'old_secret'),
                new OA\Property(property: 'newPassword', type: 'string', example: 'new_secret_123'),
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Пароль успешно изменён',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Пароль успешно изменён'),
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Ошибка бизнес-логики (неверный старый пароль и т.п.)',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Неверный текущий пароль'),
            ]
        )
    )]
    #[Route(
        '/update-user-password',
        name: 'updateUserPassword',
        methods: ['POST']
    )]
    public function updateUserPassword(Request $request, CheckUserPasswordUseCase $checkUserPasswordUseCase, UpdateUserPasswordUseCase $updateUserPasswordUseCase, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->toArray();
        $allowFields = ['old_password', 'password', 'password_confirmation'];

        // Валидируем данные
        $webUser = new PasswordValidatorDto();
        foreach ($allowFields as $field) {
            $webUser->{$field} = $data[$field] ?? null;
        }

        $errors = $validator->validate($webUser);
        if ($errors->count()) {
            $errorsArr = [];
            foreach ($errors as $error)
                $errorsArr[] = $error->getMessage();

            throw new ValidationException($errorsArr, implode(".\n", $errorsArr));
        }

        $uid = UserSessionService::getUserId();

        $checkUserPasswordUseCase->handle($uid, $data['old_password'] ?? '');

        $webUseDto = new WebUserRequestDto(id: $uid, passwd_hash: $data['password']);

        return $this->response($updateUserPasswordUseCase->handle($uid, $webUseDto), 'Пользовательский пароль обновлен');

    }
}
