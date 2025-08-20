<?php

namespace App\Modules\UserCabinet\Controllers\CustomController;

use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends AbstractController
{
    protected function checkAuth(): void
    {
        if (!UserSession::checkAuth()) {
            throw new HttpException(401,'User is not authorized');
        }
    }

    protected function json2($data, $code): JsonResponse
    {
        return new JsonResponse(['code'=> $code, 'data'=>$data]);
    }

}
