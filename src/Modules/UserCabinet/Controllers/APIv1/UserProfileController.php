<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Exception\ValidationException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkUserProfileService;
use App\Modules\UserCabinet\Service\Dto\Validator\PasswordValidatorDto;
use App\Modules\UserCabinet\Service\Dto\Validator\WebUserValidatorDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Modules\Common\Domain\Service\Dto\Request\WebUserDto as WebUserRequestDto;
use OpenApi\Attributes as OA;

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
    public function getShortUserInfo(LkUserProfileService $userInfoService): JsonResponse
    {
        $dtoResponse = $userInfoService->getShortUserInfo(UserSessionService::getUserId());
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
    public function updateUserInfo(Request $request, LkUserProfileService $userProfileService, ValidatorInterface $validator): JsonResponse
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
        return $this->response($userProfileService->updateUserInfo($uid, $webUseDto), 'Пользовательская информация обновлена');
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
    public function updateUserPassword(Request $request, LKUserProfileService $userProfileService, ValidatorInterface $validator): JsonResponse
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

        // проверка, нужно внести в сервис
        $userProfileService->checkPassword($data['old_password'] ?? false);

        $webUseDto = new WebUserRequestDto(id: UserSessionService::getUserId(), passwd_hash:$data['password']);

        $uid = UserSessionService::getUserId();

        return $this->response($userProfileService->updateUserPassword($uid, $webUseDto), 'Пользовательский пароль обновлен');

    }
}
