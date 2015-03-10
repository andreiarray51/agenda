<?php

namespace Array51\AgendaBundle\Response\V1_1\Calendar;

use JMS\Serializer\Annotation as JMS;

class DayResponse
{
    /**
     * @var string
     *
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("day")
     */
    private $day;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     * @JMS\SerializedName("events")
     */
    private $events;

    /**
     * @param array $day
     */
    public function __construct(array $day)
    {
        $this->setDay($day['date']);
        $this->setEvents($day['events']);
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return int
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param int $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }
}
