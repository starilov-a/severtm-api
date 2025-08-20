<?php

namespace App\Modules\UserCabinet\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthListener
{
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            [$controllerObject] = $controller;

            if (method_exists($controllerObject, 'authenticate')) {
                $result = $controllerObject->authenticate();
                if ($result !== true) {
                    $event->setController(function() use ($result) {
                        return new RedirectResponse('/login');
                    });
                }
            }
        }
    }
}
