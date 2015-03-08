<?php

namespace Array51\AgendaBundle\EventListener;

use JMS\Serializer\Serializer;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Array51\AgendaBundle\Response\Error\ErrorResponse;
use Array51\AgendaBundle\Exception\AbstractBaseException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $errorResponse = new ErrorResponse();
        $statusCode = Codes::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'Sorry, a server error has occurred';

        if ($exception instanceof AbstractBaseException) {
            $statusCode = Codes::HTTP_BAD_REQUEST;
            $message = $exception->getMessage();
            $errorResponse->setErrors($exception->getErrors());
        }

        $errorResponse->setErrorMessage($message);
        $format = $event->getRequest()->getRequestFormat();
        $content = $this->serializer->serialize($errorResponse, $format);

        $response = new Response();
        $response->setContent($content);
        $response->setStatusCode($statusCode);

        $event->setResponse($response);
    }
}
