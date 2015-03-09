<?php

namespace Array51\AgendaBundle\Tests\Service;

abstract class AbstractBaseServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMockBuilder(
            '\Symfony\Component\DependencyInjection\ContainerInterface'
        )->disableOriginalConstructor()
            ->setMockClassName('ContainerInterface')
            ->getMock();
    }
}
