<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/login', name: 'app_login', methods: ['POST'])]

    public function login(): Response {
        // логика обработки авторизации ...
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController - POST',
        ]);
    }
}
