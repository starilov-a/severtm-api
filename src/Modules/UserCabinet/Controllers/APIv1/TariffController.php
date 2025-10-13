<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\ClientTariffService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TariffController extends AbstractController {

    public function authenticate(): bool
    {
        return false;
    }

    #[Route(
        '/get-current-tariff/{uid}',
        name: 'getCurrentTariff',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getCurrentTariff(int $uid, ClientTariffService $tariffService)
    {
        $responseDto = $tariffService->getCurrentTariff($uid);
        return $this->json([
            'data' => $responseDto->toArray()
        ]);
    }

    #[Route(
        '/change-next-tariff',
        name: 'changeNextTariff',
        methods: ['POST'],
        requirements: ['tariff_id' => '\d{5}']
    )]
    public function changeNextTariff(Request $request, ClientTariffService $tariffService)
    {
        $uid = 20000031;//UserSessionService::getUserId();
        $tariffId = $request->get('tariff_id');

        $tariffService->changeNextTariff($uid, $tariffId);

        return $this->json([
            'message' => 'Тариф на следующий месяц успешно изменен'
        ]);
    }
}
