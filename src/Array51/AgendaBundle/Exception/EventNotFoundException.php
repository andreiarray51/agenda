<?php

namespace Array51\AgendaBundle\Exception;

class EventNotFoundException extends NotFoundException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        $message = 'Event not found',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
