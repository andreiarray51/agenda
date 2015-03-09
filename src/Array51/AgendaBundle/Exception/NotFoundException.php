<?php

namespace Array51\AgendaBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundException extends AbstractBaseException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        $code = 0,
        \Exception $previous = null
    ) {
        throw new NotFoundHttpException($message, $previous, $code);
    }
}
