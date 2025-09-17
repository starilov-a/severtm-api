<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\UserCabinet\Controllers\CustomController\UserSession;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
class AuthListener
{
    protected \Throwable $e;
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            [$controllerObject] = $controller;

            if (method_exists($controllerObject, 'authenticate')) {
                $result = $controllerObject->authenticate();
                if ($result && UserSession::checkAuth()) {
                    throw new Exception("User is not found",Response::HTTP_UNAUTHORIZED);
                }
            }
        }
    }
}
