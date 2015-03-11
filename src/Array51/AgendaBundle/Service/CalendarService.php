<?php

namespace Array51\AgendaBundle\Service;

use Array51\DataBundle\Repository\EventRepository;
use Array51\AgendaBundle\Exception\InvalidDateException;

class CalendarService extends AbstractBaseService
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param EventRepository $eventRepository
     */
    public function setEventRepository(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param string $month Format YYYY-mm
     * @return array
     * @throws InvalidDateException
     */
    public function getMonth($month)
    {
        $date = $this->getDate($month);

        return $this->eventRepository
            ->getEventsCountByMonthDays($date['year'], $date['month']);
    }

    /**
     * If empty month, returns data
     * from current date, else from requested
     *
     * @param string $month
     * @return array
     * @throws InvalidDateException
     */
    private function getDate($month)
    {
        if (0 == strlen($month)) {
            $date = new \DateTime();
        } else {
            $date = \DateTime::createFromFormat('Y-m', $month);
            if (false === $date) {
                throw new InvalidDateException(
                    'Invalid format for month, required format is YYYY-mm (2015-03)'
                );
            }
        }

        return [
            'year' => $date->format('Y'),
            'month' => $date->format('m'),
        ];
    }
}
