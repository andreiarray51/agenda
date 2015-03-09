<?php

namespace Array51\AgendaBundle\Exception;

abstract class AbstractBaseException extends \Exception
{
    /**
     * @var array
     */
    private $options = [
        'errors' => [],
        'status_code' => null,
    ];

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     * @param array $options
     */
    public function __construct(
        $message,
        $code = 0,
        \Exception $previous = null,
        array $options = []
    ) {
        parent::__construct($message, $code, $previous);

        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
