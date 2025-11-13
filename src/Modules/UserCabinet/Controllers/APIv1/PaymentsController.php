<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\UserPaymentsService;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PaymentsController extends Controller
{


    #[Route(
        '/get-balance/',
        name: 'getBalance',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getBalance(UserPaymentsService $paymentsService)
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getBalance($uid));
    }

    #[Route(
        '/get-write-offs/',
        name: 'getWriteOffs',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getWriteOffs(Request $request, UserPaymentsService $paymentsService)
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
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getReplenishments(Request $request, UserPaymentsService $paymentsService)
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
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getDebt(UserPaymentsService $paymentsService)
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getDebt($uid));
    }
}