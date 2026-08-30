<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $event->setResponse(new JsonResponse(['errors' => [[
            'message' => $event->getThrowable()->getMessage(),
            'extensions' => []
        ]]], 400));
    }
}
