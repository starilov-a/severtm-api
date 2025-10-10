<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
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
    protected LoggerService $loggerService;

    public function __construct(
        WebHistoryService $webHistoryService,
        LoggerService $loggerService,
    )
    {
        $this->webHistoryService = $webHistoryService;
        $this->loggerService = $loggerService;
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $status = match (true) {
            $e instanceof ImportantBusinessException    => 400,
            default                                     => 500,
        };

        if ($e instanceof ImportantBusinessException) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $e->getUserId(),
                $e->getActionId(),
                $e->getMessage(),
                $e->getStatus(),
                $e->getIp()
            ));
        }


        $responseData = [
            'message' => $e->getMessage()
        ];
        $response = new JsonResponse($responseData, 404);
        $event->setResponse($response);
    }

}
