<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Domain\UserProfileService;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

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
}
