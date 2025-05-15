<?php


namespace App\Modules\UserCabinet\Controllers\APIv1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\Console\CI\debug;

class Controller extends AbstractController
{
    #[Route('/get-short-user-info', name: 'getShortUserInfo', methods: ['GET'])]
    public function getShortUserInfo(): Response
    {
        return new Response('Краткая информация о пользователе');
    }
}