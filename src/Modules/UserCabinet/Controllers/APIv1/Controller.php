<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\UserCabinet\Controllers\CustomController\BaseController;
use App\Modules\UserCabinet\Service\Dto\FilterDto;
use App\Modules\UserCabinet\Service\PaymentsService;
use App\Modules\UserCabinet\Service\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends BaseController
{

    private function authenticate(): true
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
        try{
            $this->checkAuth();
        }catch (\Exception $e){
            return $this->json2($e->getMessage(), 401);
        }

        return $this->json([1,2,3,4]);
    }


    #[Route(
        '/get-short-user-info/{uid}',
        name: 'getShortUserInfo',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getShortUserInfo(int $uid, UserProfileService $userInfoService)
    {
        return $this->json($userInfoService->getShortUserInfo($uid));
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
