<?php

namespace Array51\AgendaBundle\Exception;

class InvalidEventException extends BadRequestException
{
    /**
     * @param array $errors
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        array $errors = [],
        $message = 'Invalid event data',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $errors, $code = 0, $previous);
    }
}
