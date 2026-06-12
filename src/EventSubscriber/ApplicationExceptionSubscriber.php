<?php

namespace App\EventSubscriber;

use App\Exception\ApplicationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApplicationExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => ['onException', 100],
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApplicationException) {
            $event->setResponse(
                new JsonResponse(
                    $exception->getResponsePayload(), $exception->getResponseStatus(),
                ),
            );
        }
    }
}
