<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Exception\RepositoryException;
use App\Modules\UserCabinet\Service\WebHistoryService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ExceptionListener
{
    protected \Throwable $e;
    protected WebHistoryService $webHistoryService;

    public function __construct(WebHistoryService $webHistoryService)
    {
        $this->webHistoryService = $webHistoryService;
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $status = match (true) {
            $exception instanceof BusinessException     => 400,
            $exception instanceof RepositoryException   => 500,
            default                                     => 500,
        };


        $responseData = [
            'message' => $exception->getMessage()
        ];
        $response = new JsonResponse($responseData, 404);
        $event->setResponse($response);
    }

}
