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

class UserProfileController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[Route(
        '/get-short-user-info',
        name: 'getShortUserInfo',
        methods: ['GET', 'POST']
    )]
    public function getShortUserInfo(LkUserProfileService $userInfoService): JsonResponse
    {
        $dtoResponse = $userInfoService->getShortUserInfo(UserSessionService::getUserId());
        return $this->responseData($dtoResponse);
    }

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

        if (count($errors = $validator->validate($webUser)) > 1) {
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

    #[Route(
        '/get-reason-for-freeze',
        name: 'getReasonForFreeze',
        methods: ['GET']
    )]
    public function getReasonForFreeze(LKUserProfileService $userProfileService)
    {
        return $this->response($userProfileService->getReasonForFreeze(), 'Список доступных причин заморозки');
    }

    #[Route(
        '/freeze',
        name: 'freeze',
        methods: ['POST']
    )]
    public function freeze(Request $request, LKUserProfileService $userProfileService)
    {
        //TODO: сделать валидацию
        $data = $request->toArray();
        $uid = UserSessionService::getUserId();

        $userProfileService->freezeProfile($uid, $data['startDate'], $data['comment']);

        return $this->responseMessage('Аккаунт будет заморожен с указанного числа!');
    }
}
