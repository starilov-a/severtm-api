<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use App\Modules\UserCabinet\Service\UserInformation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[Route(
        '/get-short-user-info/{uid}',
        name: 'getShortUserInfo',
        methods: ['GET'],
        requirements: ['uid' => '\d{8}']
    )]
    public function getShortUserInfo(int $uid, UserInformation $userInfoService)
    {
        return $this->json($userInfoService->getShortUserInfo($uid));
    }
}