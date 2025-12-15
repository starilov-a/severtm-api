<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkPaymentsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PaymentsController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[Route(
        '/get-balance/',
        name: 'getBalance',
        methods: ['GET']
    )]
    public function getBalance(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getBalance($uid));
    }

    #[Route(
        '/get-write-offs/',
        name: 'getWriteOffs',
        methods: ['GET']
    )]
    public function getWriteOffs(Request $request, LkPaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto();
        $filterDto->setLimit($request->query->get('limit'));
        $filterDto->setOffset($request->query->get('offset'));

        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getWriteOffs($uid, $filterDto));
    }

    #[Route(
        '/get-replenishments/',
        name: 'getReplenishments',
        methods: ['GET']
    )]
    public function getReplenishments(Request $request, LkPaymentsService $paymentsService): JsonResponse
    {
        $filterDto = new FilterDto();
        $filterDto->setLimit($request->query->get('limit'));
        $filterDto->setOffset($request->query->get('offset'));

        $uid = UserSessionService::getUserId();

        return $this->responseData($paymentsService->getReplenishments($uid, $filterDto));
    }

    #[Route(
        '/get-debt/',
        name: 'getDebt',
        methods: ['GET']
    )]
    public function getDebt(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getDebt($uid));
    }
}
