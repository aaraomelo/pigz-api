<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener]
class ExceptionListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $code = $exception->getCode();
        $message = $exception->getMessage();
        json_decode($message);
        $message = json_last_error() === JSON_ERROR_NONE ? $message : json_encode($message);
        $message = sprintf(
            '{"code": %s, "data": %s}',
            $code,
            $message,
        );
        $response = new Response(
            $message
        );
        $response->headers->set('Content-Type', 'application/json;charset=UTF-8');
        $response->setStatusCode($code);
        $event->setResponse($response);
    }
}
