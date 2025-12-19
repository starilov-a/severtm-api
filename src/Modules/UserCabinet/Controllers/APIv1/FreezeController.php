<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkFreezeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FreezeController extends Controller
{
    public function authenticate(): bool
    {
        return true;
    }

    #[Route(
        '/get-reason-for-freeze',
        name: 'getReasonForFreeze',
        methods: ['GET']
    )]
    public function getReasonForFreeze(LkFreezeService $freezeService)
    {
        return $this->response($freezeService->getReasonForFreeze(), 'Список доступных причин заморозки');
    }

    #[Route(
        '/enable-freeze',
        name: 'enableFreeze',
        methods: ['POST']
    )]
    public function enableFreeze(Request $request, LkFreezeService $freezeService): JsonResponse
    {
        //TODO: сделать валидацию
        $data = $request->toArray();
        $uid = UserSessionService::getUserId();

        $freezeService->freezeProfile($uid, $data['startDate'], $data['reason_id']);

        return $this->responseMessage('Аккаунт будет заморожен с указанного числа!');
    }

    #[Route(
        '/disable-freeze',
        name: 'disableFreeze',
        methods: ['POST']
    )]
    public function disableFreeze(LkFreezeService $freezeService): JsonResponse
    {
        $uid = UserSessionService::getUserId();

        $freezeService->unfreezeProfile($uid);

        return $this->responseMessage('Аккаунт разморожен!');
    }

    #[Route(
        '/get-status-freeze',
        name: 'getStatusFreeze',
        methods: ['GET']
    )]
    public function getStatusFreeze(LkFreezeService $freezeService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $freezeService->unfreezeProfile($uid);

        return $this->responseMessage('Аккаунт разморожен!');
    }
}