<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    public function authenticate(): bool
    {
        return false;
    }
    #[Route('/login', name: 'app_login_get', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([1,2,3,4]);
    }
    #[Route('/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $login = $body['login'];
        $password = $body['password'];
       return $this->json([$login]);

    }
}
