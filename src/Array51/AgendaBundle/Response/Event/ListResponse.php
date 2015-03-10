<?php

namespace Array51\AgendaBundle\Response\Event;

use Array51\AgendaBundle\Response\AbstractBaseResponse;
use JMS\Serializer\Annotation as JMS;

class ListResponse extends AbstractBaseResponse
{
    /**
     * @var array
     *
     * @JMS\SerializedName("events")
     */
    private $events;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     * @JMS\SerializedName("total")
     */
    private $total;

    /**
     * @param array $events
     * @param int $total
     */
    public function __construct(array $events, $total)
    {
        if (0 == count($events)) {
            $this->setErrorMessage('No events found');
        }

        foreach ($events as $event) {
            $this->addEvent($event);
        }

        $this->setTotal($total);
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param array $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @param array $event
     */
    public function addEvent(array $event)
    {
        $this->events []= new ListEventResponse($event);
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}
