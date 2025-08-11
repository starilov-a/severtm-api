<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\UserCabinet\Service\Exception\BusinessException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ExceptionListener
{
    protected \Throwable $e;

    public function __invoke(ExceptionEvent $event): void
    {
        $this->e = $event->getThrowable();

        if ($this->e instanceof BusinessException) {
            $this->setResponse($event);
        }

        return;
    }

    protected function setResponse(ExceptionEvent $event): void
    {
        $payload = [
            'code'    => $this->e->getCodeKey(),
            'message' => $this->e->getMessage(),
        ];

        $event->setResponse(new JsonResponse(
            $payload, 
            $this->e->getHttpStatus()
        ));
    }
}
