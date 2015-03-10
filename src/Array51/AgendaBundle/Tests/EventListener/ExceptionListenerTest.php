<?php

namespace Array51\AgendaBundle\EventListener;

use JMS\Serializer\Serializer;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use FOS\RestBundle\Util\Codes;
use Array51\AgendaBundle\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Array51\AgendaBundle\Response\Error\ErrorResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ExceptionListenerTest extends \PHPUnit_Framework_TestCase
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
     * @var Request
     */
    private $request;

    /**
     * @var GetResponseForExceptionEvent
     */
    private $event;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder(
            'JMS\Serializer\Serializer'
        )
            ->setMockClassName('Serializer')
            ->setMethods(['serialize'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMockBuilder(
            'Symfony\Bridge\Monolog\Logger'
        )
            ->setMockClassName('Logger')
            ->setMethods(['error'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = $this->getMockBuilder(
            'Symfony\Component\HttpFoundation\Request'
        )
            ->setMockClassName('Request')
            ->setMethods(['error', 'getRequestFormat'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->request->query = $this->getMockBuilder(
            'Symfony\Component\HttpFoundation\ParameterBag'
        )
            ->setMockClassName('ParameterBag')
            ->setMethods(['all'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->request->request = $this->getMockBuilder(
            'Symfony\Component\HttpFoundation\ParameterBag'
        )
            ->setMockClassName('ParameterBag')
            ->setMethods(['all'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->event = $this->getMockBuilder(
            'Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent'
        )
            ->setMockClassName('GetResponseForExceptionEvent')
            ->setMethods(['getException', 'getRequest', 'setResponse'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testOnKernelExceptionWithAbstractBaseException()
    {
        $exceptionMessage = 'Exception message';
        $exceptionErrors = [
            'errors' => null,
            'status_code' => 400,
        ];

        $eventNotFoundException = new BadRequestException(
            $exceptionMessage,
            $exceptionErrors
        );

        $this->request->expects($this->once())
            ->method('getRequestFormat')
            ->will($this->returnValue('json'));

        $errorResponse = new ErrorResponse();
        $errorResponse->setErrorMessage($exceptionMessage);
        $errorResponse->setErrors($exceptionErrors);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($errorResponse, 'json');

        $this->event->expects($this->once())
            ->method('getException')
            ->will($this->returnValue($eventNotFoundException));
        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));

        $this->event->expects($this->once())
            ->method('setResponse');

        $exceptionListener = new ExceptionListener($this->serializer, $this->logger);
        $exceptionListener->onKernelException($this->event);
    }

    public function testOnKernelExceptionWitOtherException()
    {
        $exceptionMessage = sprintf('Sorry, a server error has occurred (%d)', time());

        $exception = new \Exception($exceptionMessage);

        $this->request->query->expects($this->once())
            ->method('all')
            ->will($this->returnValue([]));
        $this->request->request->expects($this->once())
            ->method('all')
            ->will($this->returnValue([]));

        $logEntry = [
            'status_code' => Codes::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exceptionMessage,
            'query' => [],
            'request' => [],
        ];
        $this->logger->expects($this->once())
            ->method('error')
            ->with(serialize($logEntry))
            ->will($this->returnValue([]));

        $this->request->expects($this->once())
            ->method('getRequestFormat')
            ->will($this->returnValue('json'));

        $errorResponse = new ErrorResponse();
        $errorResponse->setErrorMessage($exception->getMessage());

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($errorResponse, 'json');

        $this->event->expects($this->once())
            ->method('getException')
            ->will($this->returnValue($exception));
        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));

        $this->event->expects($this->once())
            ->method('setResponse');

        $exceptionListener = new ExceptionListener($this->serializer, $this->logger);
        $exceptionListener->onKernelException($this->event);
    }
    public function testOnKernelExceptionWithHttpException()
    {
        $exceptionMessage = 'Exception message';
        $exceptionStatusCode = 400;

        $httpException = new HttpException(
            $exceptionStatusCode,
            $exceptionMessage
        );

        $this->request->expects($this->once())
            ->method('getRequestFormat')
            ->will($this->returnValue('json'));

        $errorResponse = new ErrorResponse();
        $errorResponse->setErrorMessage($httpException->getMessage());

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($errorResponse, 'json');

        $this->event->expects($this->once())
            ->method('getException')
            ->will($this->returnValue($httpException));
        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));

        $this->event->expects($this->once())
            ->method('setResponse');

        $exceptionListener = new ExceptionListener($this->serializer, $this->logger);
        $exceptionListener->onKernelException($this->event);
    }
}
