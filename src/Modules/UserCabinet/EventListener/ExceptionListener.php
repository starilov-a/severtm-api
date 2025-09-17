<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\UserCabinet\Service\Exception\BusinessException;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ExceptionListener
{
    protected \Throwable $e;

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $responseData = [
            'message' => $exception->getMessage()
        ];
        $response = new JsonResponse($responseData, 404);
        $event->setResponse($response);
    }

}
