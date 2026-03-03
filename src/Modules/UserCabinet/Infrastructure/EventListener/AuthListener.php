<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\UserCabinet\Infrastructure\Exception\AuthException;
use App\Modules\UserCabinet\Infrastructure\Service\Auth\Service\Auth;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::CONTROLLER)]
final class AuthListener
{

    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    protected \Throwable $e;
    public function onKernelController(ControllerEvent $event): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
            session_start();

        $controller = $event->getController();
        if (!is_array($controller)) {
           return;
        }

        [$controllerObject] = $controller;

        if (!method_exists($controllerObject, 'authenticate')) {
            return;
        }

        if ($controllerObject->authenticate() && !$this->auth->checkAuth()) {
            throw new AuthException("Необходима авторизация");
        }
    }
}
