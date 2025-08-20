<?php

namespace App\Modules\UserCabinet\EventListener;

use App\Modules\Common\CustomController\UserSession;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class AuthListener
{
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            [$controllerObject] = $controller;

            if (method_exists($controllerObject, 'authenticate')) {
                $result = $controllerObject->authenticate();
                if ($result !== true && !UserSession::checkAuth()) {
                    $event->setController(function() use ($result) {
                        return new RedirectResponse('/login');
                    });
                }
            }
        }
    }
}
