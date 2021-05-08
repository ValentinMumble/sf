<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        $response = new JsonResponse([
            'name' => get_class($exception),
            'message' => $exception->getMessage()
        ], $statusCode);
        $event->setResponse($response);
    }
}
