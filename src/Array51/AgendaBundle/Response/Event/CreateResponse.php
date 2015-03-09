<?php

namespace Array51\AgendaBundle\Response\Event;

use Array51\AgendaBundle\Response\AbstractBaseResponse;
use JMS\Serializer\Annotation as JMS;

class CreateResponse extends AbstractBaseResponse
{
    /**
     * @var EventResponse
     *
     * @JMS\SerializedName("event")
     */
    private $event;

    /**
     * @param array $event
     */
    public function __construct(array $event)
    {
        $this->event = new EventResponse($event);
    }
}
