<?php

namespace Array51\AgendaBundle\Response\V1_1\Calendar;

use Array51\AgendaBundle\Response\AbstractBaseResponse;
use JMS\Serializer\Annotation as JMS;

class CalendarResponse extends AbstractBaseResponse
{
    /**
     * @var array
     *
     * @JMS\SerializedName("calendar")
     */
    private $calendar = [];

    /**
     * @param array $calendar
     */
    public function __construct(array $calendar)
    {
        if (0 == count($calendar)) {
            $this->setErrorMessage('No events found for requested month');
        }

        foreach ($calendar as $day) {
            $this->addCalendar($day);
        }

    }

    /**
     * @return array
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param array $calendar
     */
    public function setCalendar($calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param array $event
     */
    public function addCalendar(array $event)
    {
        $this->calendar [] = new DayResponse($event);
    }
}
