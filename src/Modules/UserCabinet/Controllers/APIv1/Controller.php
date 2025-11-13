<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use UserSession;

//use App\Modules\UserCabinet\Service\Dto\FilterDto;

class Controller extends AbstractController
{

    public function responseMessage(string $message): JsonResponse
    {
        return $this->json(['message' => $message]);
    }

    public function responseData(mixed $data): JsonResponse
    {
        return $this->json(['data' => $data]);
    }

    public function response(mixed $data, string $message): JsonResponse
    {
        return $this->json(
            [
                'data' => $data,
                'message' => $message
            ]
        );
    }

    // Данный метод нужен для того, чтобы подвязать конкретный контроллер к listener
    // В нашем случаем данный метод прослушивается в AuthListener.php
    // данный метод стоит присваивать каждому контроллеру,
    // тк если вынести это в BaseController, то будет проверяться авторизация каждый раз!

    // Важно!!! Все контроллеры стоит наследовать от BaseController
    public function authenticate(): bool
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
        return $this->json([1, 2, 3, 4]);
    }
}
