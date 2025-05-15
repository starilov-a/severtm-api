<?php


namespace App\Modules\UserCabinet\Controllers\APIv1;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller
{
    #[Route('/get-short-user-info', name: 'getShortUserInfo', methods: ['GET'])]
    public function getShortUserInfo(): Response
    {
        return new Response('Краткая информация о пользователе');

        dd(123);
    }
}