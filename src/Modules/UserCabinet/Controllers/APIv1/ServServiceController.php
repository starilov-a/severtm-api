<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkClientServService;
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
}