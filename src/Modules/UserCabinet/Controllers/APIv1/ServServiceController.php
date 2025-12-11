<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkClientServService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ServServiceController extends Controller
{
    #[Route(
        '/get-available-servs',
        name: 'getAvailableServs',
        methods: ['GET']
    )]
    public function getAvailableServs(LkClientServService $servService)
    {
        $data = $servService->listAvailableServices();

        return $this->responseData($data);
    }
    #[Route(
        '/get-current-servs',
        name: 'getCurrentServs',
        methods: ['GET']
    )]
    public function getCurrentServs(LkClientServService $servService)
    {
        $uid = UserSessionService::getUserId();
        $data = $servService->getCurrentServices($uid);

        return $this->responseData($data);
    }

    #[Route(
        '/add-available-serv',
        name: 'addAvailableServ',
        methods: ['POST'],
        requirements: ['mode_id' => '\d{3,6}']
    )]
    public function addAvailableServ(Request $request, LkClientServService $servService)
    {
        $uid = UserSessionService::getUserId();
        $modeId = $request->get('mode_id');

        $servService->enableService($uid, $modeId);

        return $this->json([
            'message' => 'Успешное добавление услуги!'
        ]);
    }
}