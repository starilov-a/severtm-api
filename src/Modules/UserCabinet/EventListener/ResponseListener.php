<?php

namespace App\Modules\UserCabinet\EventListener;


use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;


#[AsEventListener(event: KernelEvents::RESPONSE)]
class ResponseListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        // проверка, на то что не используется в контроллере view/twig
        if (!$response instanceof JsonResponse || $response->getStatusCode() !== 200) {
            return;
        }

        $arrayResponse = json_decode($response->getContent(), true);
        $data['data'] = !empty($arrayResponse['data']) ?  $arrayResponse['data'] : null;
        $data['message'] = !empty($arrayResponse['message']) ?  $arrayResponse['data'] : null;

        $result = new JsonResponse(
            $data,
            $response->getStatusCode(),
            $response->headers->all()
        );
        $event->setResponse($result);
    }
}
