<?php

namespace Array51\AgendaBundle\EventListener;

use Array51\AgendaBundle\Controller\TokenAuthenticatedControllerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;

class TokenListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilterControllerEvent
     */
    private $event;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var TokenAuthenticatedControllerInterface
     */
    private $tokenInterface;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->getMockBuilder(
            'Symfony\Component\HttpKernel\Event\FilterControllerEvent'
        )
            ->setMockClassName('FilterControllerEvent')
            ->setMethods(['getController', 'getRequest'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = $this->getMockBuilder(
            'Symfony\Component\HttpFoundation\Request'
        )
            ->setMockClassName('Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenInterface = $this->getMockBuilder(
            'Array51\AgendaBundle\Controller\TokenAuthenticatedControllerInterface'
        )
            ->setMockClassName('TokenAuthenticatedControllerInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAllow()
    {
        $tokens = ['user' => 'token value',];

        $this->event->expects($this->once())
            ->method('getController')
            ->will($this->returnValue([$this->tokenInterface]));

        $this->request->query = new ParameterBag(['token' => 'token value',]);

        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));

        $tokenListener = new TokenListener($tokens);
        $tokenListener->onKernelController($this->event);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testDeny()
    {
        $tokens = ['user' => 'user token',];

        $this->event->expects($this->once())
            ->method('getController')
            ->will($this->returnValue([$this->tokenInterface]));

        $this->request->query = new ParameterBag(['token' => 'another token ',]);

        $this->event->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request));

        $tokenListener = new TokenListener($tokens);
        $tokenListener->onKernelController($this->event);
    }
}
