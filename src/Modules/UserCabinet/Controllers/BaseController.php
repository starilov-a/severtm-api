<?php

namespace App\Modules\UserCabinet\Controllers;

use App\Modules\UserCabinet\Controllers\CustomController\UserSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends AbstractController
{

// Этот метод также не нужен, тк проверка происходит в AuthListener
//    protected function checkAuth(): void
//    {
//        if (!UserSession::checkAuth()) {
//            throw new HttpException(444,'User is not authorized');
//        }
//    }


    // этот метод мы заменим и вынесем в EventListener!
//    protected function json2($data, $code): JsonResponse
//    {
//        return new JsonResponse(['code'=> $code, 'data'=>$data]);
//    }

}
