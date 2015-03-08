<?php

namespace Array51\AgendaBundle\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use FOS\RestBundle\Util\Codes;
use Array51\AgendaBundle\Exception\AbstractBaseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Array51\AgendaBundle\Response\Error\ErrorResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ExceptionListener
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * @param Serializer $serializer
     * @param Logger $logger
     */
    public function __construct(Serializer $serializer, Logger $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();

        $errorResponse = new ErrorResponse();

        if ($exception instanceof AbstractBaseException) {
            $statusCode = Codes::HTTP_BAD_REQUEST;
            $message = $exception->getMessage();
            $errorResponse->setErrors($exception->getErrors());
        } elseif ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        } else {
            $statusCode = Codes::HTTP_INTERNAL_SERVER_ERROR;
            $message = 'Sorry, a server error has occurred';
            $this->logError($request, $statusCode, $message);
        }

        $errorResponse->setErrorMessage($message);
        $format = $request->getRequestFormat();
        $content = $this->serializer->serialize($errorResponse, $format);

        $response = new Response();
        $response->setContent($content);
        $response->setStatusCode($statusCode);

        $event->setResponse($response);
    }

    /**
     * @param Request $request
     * @param int $statusCode
     * @param string $message
     */
    private function logError(Request $request, $statusCode, $message)
    {
        $logEntry = array(
            'status_code' => $statusCode,
            'message' => $message,
            'query' => $request->query->all(),
            'request' => $request->request->all(),
        );
        $this->logger->error(serialize($logEntry));
    }
}
