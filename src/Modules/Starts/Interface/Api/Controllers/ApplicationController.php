<?php

namespace App\Modules\Starts\Interface\Api\Controllers;

use App\Modules\Common\Interface\Api\Controller;
use App\Modules\Starts\Application\UseCase\GetBuilderApplicationUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ApplicationController extends Controller
{
    #[Route(
        '/get-builder-application-list',
        name: 'getBuilderApplicationList',
        methods: ['GET']
    )]
    public function getBuilderApplicationList(Request $request, GetBuilderApplicationUseCase $useCase): JsonResponse
    {
        $uid = 20000021;
        $list = $useCase->handle($uid);
        return $this->responseData($list);
    }
}