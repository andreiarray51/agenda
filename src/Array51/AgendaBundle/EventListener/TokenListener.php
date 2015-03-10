<?php

namespace Array51\AgendaBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Array51\AgendaBundle\Controller\TokenAuthenticatedControllerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TokenListener
{
    /**
     * @var array
     */
    private $tokens;

    /**
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedControllerInterface) {
            $token = $event->getRequest()->query->get('token');
            if (!in_array($token, $this->tokens)) {
                throw new AccessDeniedHttpException('A valid token is required');
            }
        }
    }
}