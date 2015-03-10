<?php

namespace Array51\AgendaBundle\Exception;

class BadRequestException extends AbstractBaseException
{
    /**
     * @param string $message
     * @param array $errors
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        array $errors = [],
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous, ['errors' => $errors,]);
    }
}
