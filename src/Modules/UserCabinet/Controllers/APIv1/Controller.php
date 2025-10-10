<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\UserCabinet\Service\PaymentsService;
use App\Modules\UserCabinet\Service\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use UserSession;

//use App\Modules\UserCabinet\Service\Dto\FilterDto;

class Controller extends AbstractController
{
    // Данный метод нужен для того, чтобы подвязать конкретный контроллер к listener
    // В нашем случаем данный метод прослушивается в AuthListener.php
    // данный метод стоит присваивать каждому контроллеру,
    // тк если вынести это в BaseController, то будет проверяться авторизация каждый раз!

    // Важно!!! Все контроллеры стоит наследовать от BaseController
    public function authenticate(): bool
    {
        return true;
    }

    #[Route(
        '/index',
        name: 'api_v1_index',
        methods: ['GET']
    )]
    public function index(): JsonResponse
    {
        return $this->json([1,2,3,4]);
    }


    #[Route(
        '/get-balance/{uid}',
        name: 'getBalance',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getBalance(int $uid, PaymentsService $paymentsService)
    {
        return $this->json($paymentsService->getBalance($uid));
    }

    #[Route(
        '/get-write-offs/{uid}',
        name: 'getWriteOffs',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getWriteOffs(Request $request, int $uid, PaymentsService $paymentsService)
    {
        $filterDto = new FilterDto($request->query->get('limit'), $request->query->get('offset'));

        return $this->json($paymentsService->getWriteOffs($filterDto, $uid));
    }

    #[Route(
        '/get-replenishments/{uid}',
        name: 'getReplenishments',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getReplenishments(Request $request, int $uid, PaymentsService $paymentsService)
    {
        $filterDto = new FilterDto($request->query->get('limit'), $request->query->get('offset'));

        return $this->json($paymentsService->getReplenishments($filterDto, $uid));
    }

    #[Route(
        '/get-debt/{uid}',
        name: 'getDebt',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getDebt(int $uid, PaymentsService $paymentsService)
    {
        return $this->json($paymentsService->getDebt($uid));
    }
}
