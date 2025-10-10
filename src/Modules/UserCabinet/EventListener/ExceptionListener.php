<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\Common\Infrastructure\Exception\AuthException;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Service\WebHistoryService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
            $e instanceof ImportantBusinessException    => Response::HTTP_BAD_REQUEST,
            $e instanceof BusinessException             => Response::HTTP_BAD_REQUEST,
            $e instanceof AuthException                 => Response::HTTP_UNAUTHORIZED,
            default                                     => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        if ($e instanceof ImportantBusinessException) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $e->getUserId(),
                $e->getActionId(),
                $e->getMessage(),
                $e->getStatus(),
                $e->getIp()
            ));
        } elseif ($status >= 500) {
            //TODO: добавить необходимые данные из запроса
            $this->loggerService->errorLog(new ErrorLogDto(
                $e->getMessage(),
                array_filter([
//                    'route'     => $this->request?->attributes->get('_route'),
//                    'method'    => $this->request?->getMethod(),
//                    'path'      => $this->request?->getPathInfo(),
//                    'query'     => $this->request?->query->all() ?: null
                ], fn($v) => $v !== null && $v !== [])
            ));
        }

        $responseData = [
            'message' => $e->getMessage(),
        ];

        $response = new JsonResponse($responseData, $status);
        $event->setResponse($response);
    }

}
