<?php

namespace App\Modules\Common\Interface\Api;

use App\Modules\UserCabinet\Application\Dto\Validator\ValidatorDto;
use App\Modules\UserCabinet\Infrastructure\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class Controller extends AbstractController
{
    public function __construct(
        protected ValidatorInterface $validator
    ) {}

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

    protected function validate(object $dto, array $data): void
    {
        if (!($dto instanceof ValidatorDto))
            throw new \LogicException('Wrong validate DTO');

        foreach ($data as $key => $val)
            $dto->{$key} = $val;

        $errors = $this->validator->validate($dto);
        if ($errors->count()) {
            $errorsArr = [];
            foreach ($errors as $error)
                $errorsArr[] = $error->getMessage();

            throw new ValidationException($errorsArr, 'Ошибка валидации');
        }
    }
}
