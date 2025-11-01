<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;
use App\Modules\UserCabinet\Service\Dto\Request\WebUserDto as WebUserRequestDto;
use App\Modules\UserCabinet\Service\Dto\Validator\PasswordValidatorDto;
use App\Modules\UserCabinet\Service\Dto\Validator\WebUserValidatorDto;
use App\Modules\UserCabinet\Service\PaymentsService;
use App\Modules\UserCabinet\Service\UserProfileService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function getShortUserInfo(UserProfileService $userInfoService): JsonResponse
    {
        $dtoResponse = $userInfoService->getShortUserInfo(UserSessionService::getUserId());
        return $this->responseData($dtoResponse);
    }

    #[Route(
        '/get-full-user-info',
        name: 'getFullUserInfo',
        methods: ['GET', 'POST']
    )]
    public function getFullUserInfo(UserProfileService $userInfoService): JsonResponse
    {
        $dtoResponse = $userInfoService->getFullUserInfo(UserSessionService::getUserId());
        return $this->responseData($dtoResponse);
    }

    #[Route(
        '/get-balance',
        name: 'getBalance',
        methods: ['GET', 'POST'],
    )]
    public function getBalance(PaymentsService $paymentsService): JsonResponse
    {
        return $this->responseData($paymentsService->getBalance(UserSessionService::getUserId()));
    }

    #[Route(
        '/get-write-offs',
        name: 'getWriteOffs',
        methods: ['GET', 'POST']

    )]
    public function getWriteOffs(Request $request, PaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto($request->query->get('limit'), $request->query->get('offset'));

        $collection = $paymentsService->getWriteOffs($filterDto, UserSessionService::getUserId());

        return $this->responseData($collection->toArray());

    }

    #[Route(
        '/get-replenishments',
        name: 'getReplenishments',
        methods: ['GET', 'POST']
    )]
    public function getReplenishments(Request $request, PaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto($request->query->get('limit'), $request->query->get('offset'));

        $collection = $paymentsService->getReplenishments($filterDto, UserSessionService::getUserId());
        return $this->responseData($collection->toArray());
    }


    #[Route(
        '/update-user-info',
        name: 'updateUserInfo',
        methods: ['POST']
    )]
    public function updateUserInfo(Request $request, UserProfileService $userProfileService, ValidatorInterface $validator): JsonResponse
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
        $data['id'] = UserSessionService::getUserId();

        $webUseDto = new WebUserRequestDto(...$data);
        return $this->response($userProfileService->updateUserInfo($webUseDto), 'Пользовательская информация обновлена');

    }

    #[Route(
        '/update-user-password',
        name: 'updateUserPassword',
        methods: ['POST']
    )]
    public function updateUserPassword(Request $request, UserProfileService $userProfileService, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->toArray();
        $allowFields = ['old_password', 'password', 'password_confirmation'];

        // Валидируем данные
        $webUser = new PasswordValidatorDto();
        foreach ($allowFields as $field) {
            $webUser->{$field} = $data[$field] ?? null;
        }

        if (count($errors = $validator->validate($webUser)) > 1) {
            $errorsString = [];
            foreach ($errors as $error) {
                $errorsString[] = $error->getMessage();
            }
            return $this->response(
                $errorsString,
                implode(".\n", $errorsString),
                400
            );
        }
        // проверка, что пользователь ввел актуальный пароль
        $userProfileService->checkPassword($data['old_password'] ?? false);

        // создаем Dto
        $webUseDto = new WebUserRequestDto(id: UserSessionService::getUserId(), passwd_hash:$data['password']);

        return $this->response($userProfileService->updateUserPassword($webUseDto), 'Пользовательский пароль обновлен');

    }
}
