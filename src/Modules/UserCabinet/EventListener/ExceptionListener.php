<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\Common\Infrastructure\Exception\AuthException;
use App\Modules\Common\Infrastructure\Exception\BusinessException;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Infrastructure\Exception\ValidationException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\UserSessionService;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\BusinessLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\Dto\ErrorLogDto;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ExceptionListener
{
    protected \Throwable $e;
    protected LoggerService $loggerService;

    public function __construct(
        LoggerService $loggerService
    )
    {
        $this->loggerService = $loggerService;
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $request = $event->getRequest();

        $status = match (true) {
            $e instanceof ImportantBusinessException    => Response::HTTP_BAD_REQUEST,
            $e instanceof BusinessException             => Response::HTTP_BAD_REQUEST,
            $e instanceof AuthException                 => Response::HTTP_UNAUTHORIZED,
            $e instanceof ValidationException           => Response::HTTP_BAD_REQUEST,
            default                                     => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $message = $e->getMessage();

        if ($e instanceof ImportantBusinessException) {
            $this->loggerService->businessLog(new BusinessLogDto(
                $e->getUserId(),
                $e->getActionId(),
                $e->getMessage(),
                $e->getStatus(),
                $e->getIp()
            ));
        } elseif ($e instanceof ValidationException) {
            $response = new JsonResponse([
                'message' => $e->getMessage(),
                'data' => $e->getErrors()
            ], $status);
            $event->setResponse($response);

            return;
        } elseif ($status >= 500) {
            $this->loggerService->errorLog(new ErrorLogDto(
                $e->getMessage(),
                array_filter([
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                    'route'     => $request->attributes->get('_route'),
                    'method'    => $request->getMethod(),
                    'path'      => $request->getPathInfo(),
                    'userId'    => UserSessionService::getUserId(),
                    'query'     => $request->query->all() ? json_encode($request->query->all()) : null,
                    'request'   => $request->request->all() ? json_encode($request->request->all()) : null,
                ], fn($v) => $v !== null && $v !== [])
            ));

            $message = 'Ошибка сервера';
        }

        $responseData = [
            'message' => $message,
        ];

        $response = new JsonResponse($responseData, $status);
        $event->setResponse($response);
    }

}
