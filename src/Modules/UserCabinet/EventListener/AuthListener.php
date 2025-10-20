<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\Common\Infrastructure\Exception\AuthException;
use App\Modules\Common\Infrastructure\Service\Auth\Service\Auth;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
final class AuthListener
{
    protected \Throwable $e;
    public function onKernelController(ControllerEvent $event, Auth $auth): void
    {
        session_start();

        $controller = $event->getController();
        if (!is_array($controller)) {
           return;
        }

        [$controllerObject] = $controller;

        if (!method_exists($controllerObject, 'authenticate')) {
            return;
        }

        if (!$controllerObject->authenticate() || !$auth->checkAuth()) {
            return;
        }

        throw new AuthException("Необходима ");
    }
}
