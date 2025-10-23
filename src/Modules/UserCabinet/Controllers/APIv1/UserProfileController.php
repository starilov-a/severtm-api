<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\Dto\Request\FilterDto;
use App\Modules\UserCabinet\Service\PaymentsService;
use App\Modules\UserCabinet\Service\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function getBalance( PaymentsService $paymentsService): JsonResponse
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

        return $this->responseData($paymentsService->getWriteOffs($filterDto, UserSessionService::getUserId()));
    }

    #[Route(
        '/get-replenishments',
        name: 'getReplenishments',
        methods: ['GET', 'POST']
    )]
    public function getReplenishments(Request $request, PaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto($request->query->get('limit'), $request->query->get('offset'));

        return $this->responseData($paymentsService->getReplenishments($filterDto, UserSessionService::getUserId()));
    }
}
