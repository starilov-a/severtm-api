<?php

namespace App\Modules\UserCabinet\Controllers\APIv1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

//use App\Modules\UserCabinet\Service\Dto\FilterDto;

class Controller extends AbstractController
{

    public function responseMessage(string $message, $status = 200): JsonResponse
    {
        return $this->json(['message' => $message], $status);
    }

    public function responseData(mixed $data, $status = 200): JsonResponse
    {
        return $this->json(['data' => $data], $status);
    }

    public function response(mixed $data, string $message, $status = 200): JsonResponse
    {
        return $this->json(
            [
                'data' => $data,
                'message' => $message
            ], $status
        );
    }

    // Данный метод нужен для того, чтобы подвязать конкретный контроллер к listener
    // В нашем случаем данный метод прослушивается в AuthListener.php
    // данный метод стоит присваивать каждому контроллеру,
    // тк если вынести это в BaseController, то будет проверяться авторизация каждый раз!

    // Важно!!! Все контроллеры стоит наследовать от BaseController
    public function authenticate(): bool
    {
        return false;
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
