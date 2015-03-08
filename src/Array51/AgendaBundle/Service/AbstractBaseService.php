<?php

namespace Array51\AgendaBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractBaseService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
