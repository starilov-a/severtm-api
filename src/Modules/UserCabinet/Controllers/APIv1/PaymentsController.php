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
        '/get-balance',
        name: 'getBalance',
        methods: ['GET']
    )]
    public function getBalance(LkPaymentsService $paymentsService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        return $this->responseData($paymentsService->getBalance($uid));
    }

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

    #[Route(
        'get-payment-link',
        name: 'getPaymentLink',
        methods: ['GET']
    )]
    public function getPaymentLink(LkPaymentsService $paymentsService): JsonResponse
    {
        $link = $paymentsService->getPaymentLink(UserSessionService::getDistrict());
        return $this->responseData($link);
    }

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
