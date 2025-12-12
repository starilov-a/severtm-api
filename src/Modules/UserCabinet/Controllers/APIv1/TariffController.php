<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\LkClientTariffService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TariffController extends Controller
{

    public function authenticate(): bool
    {
        return true;
    }

    #[Route(
        '/get-current-tariff',
        name: 'getCurrentTariff',
        methods: ['GET']
    )]
    public function getCurrentTariff(Request $request, LkClientTariffService $tariffService): JsonResponse
    {
        $uid = UserSessionService::getUserId();
        $responseDto = $tariffService->getCurrentTariff($uid);
        return $this->responseData($responseDto->toArray());
    }

    #[Route(
        '/change-next-tariff',
        name: 'changeNextTariff',
        methods: ['POST'],
        requirements: ['tariff_id' => '\d{5}']
    )]
    public function changeNextTariff(Request $request, LkClientTariffService $tariffService)
    {
        $uid = UserSessionService::getUserId();
        $data = $request->toArray();
        $tariffId = $data['tariff_id'];

        $tariffService->changeNextTariff($uid, $tariffId);

        return $this->response(true, 'Тариф на следующий месяц успешно изменен');
    }

    #[Route(
        '/get-available-tariffs',
        name: 'getAvailableTariffs',
        methods: ['GET']
    )]
    public function getAvailableTariffs(LkClientTariffService $tariffService)
    {
        $uid = UserSessionService::getUserId();

        return $this->json([
            'data' => $tariffService->getAvailableTariffs($uid),
            'message' => 'Список доступных тарифов'
        ]);
    }
}
