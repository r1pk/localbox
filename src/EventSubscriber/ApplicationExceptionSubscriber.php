<?php

namespace App\EventSubscriber;

use App\Exception\ApplicationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        $payload = ['error' => 'An unexpected server error occurred'];
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;

        $exception = $event->getThrowable();

        if ($exception instanceof ApplicationException) {
            $payload = $exception->getResponsePayload();
            $code = $exception->getResponseStatus();
        }

        $event->setResponse(
            new JsonResponse($payload, $code),
        );
    }
}
