<?php

namespace Array51\AgendaBundle\Exception;

abstract class AbstractBaseException extends \Exception
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param string $message
     * @param array $errors
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        $message,
        $code = 0,
        \Exception $previous = null,
        array $errors = []
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
