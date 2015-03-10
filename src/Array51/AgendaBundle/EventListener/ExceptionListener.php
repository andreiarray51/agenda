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
            $message = $exception->getMessage();

            $exceptionOptions = $exception->getOptions();
            $errors = $exceptionOptions['errors'];
            if (count($errors) > 0) {
                $errorResponse->setErrors($exceptionOptions['errors']);
            }

            $statusCode = $exceptionOptions['status_code'] ?:
                Codes::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof HttpException) {
            $message = $exception->getMessage();
            $statusCode = $exception->getStatusCode();
        } else {
            $message = sprintf('Sorry, a server error has occurred (%d)', time());
            $statusCode = Codes::HTTP_INTERNAL_SERVER_ERROR;
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
        $logEntry = [
            'status_code' => $statusCode,
            'message' => $message,
            'query' => $request->query->all(),
            'request' => $request->request->all(),
        ];
        $this->logger->error(serialize($logEntry));
    }
}
