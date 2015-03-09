<?php

namespace Array51\AgendaBundle\Response\Event;

use Array51\AgendaBundle\Response\AbstractBaseResponse;

class GetResponse extends AbstractBaseResponse
{
    /**
     * @var EventResponse
     */
    private $event;

    /**
     * @param array $event
     */
    public function __construct($event)
    {
        $this->event = new EventResponse($event);
    }
}
