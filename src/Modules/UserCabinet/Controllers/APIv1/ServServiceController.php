<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkClientServService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ServServiceController extends Controller
{
    #[Route(
        '/get-available-servs',
        name: 'getAvailableServs',
        methods: ['GET']
    )]
    public function getAvailableServs(LkClientServService $servService): JsonResponse
    {
        $data = $servService->listAvailableServices();

        return $this->responseData($data);
    }

    #[Route(
        '/get-current-servs',
        name: 'getCurrentServs',
        methods: ['GET']
    )]
    public function getCurrentServs(LkClientServService $servService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $data = $servService->getCurrentServices($uid);

        return $this->responseData($data);
    }

    #[Route(
        '/add-available-serv',
        name: 'addAvailableServ',
        methods: ['POST']
    )]
    public function addAvailableServ(Request $request, LkClientServService $servService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $data = $request->toArray();

        $servService->enableService($uid, $data['mode_id']);

        return $this->response(
            true,
            'Успешное добавление услуги!'
        );
    }

    #[Route(
        '/disable-serv',
        name: 'disableServ',
        methods: ['POST']
    )]
    public function disableServ(Request $request, LkClientServService $servService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $data = $request->toArray();

        $servService->disableService($uid, $data['mode_id']);

        return $this->response(
            true,
            'Услуга отключена.'
        );
    }
}
