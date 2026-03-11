<?php

namespace App\Modules\BuildermanCabinet\Adapter\Api\Controllers;

use App\Modules\Common\Adapter\Api\Controller;
use App\Modules\BuildermanCabinet\Application\UseCase\GetBuilderApplicationUseCase;
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
        $uid = 21170816;
        $list = $useCase->execute($uid);
        return $this->responseData($list);
    }
}