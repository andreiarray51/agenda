<?php

namespace Array51\AgendaBundle\Tests\Service;

use Array51\AgendaBundle\Service\CalendarService;
use Array51\DataBundle\Repository\EventRepository;

class CalendarServiceTest extends AbstractBaseServiceTest
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->eventRepository = $this->getMockBuilder(
            'Array51\DataBundle\Repository\EventRepository'
        )
            ->setMethods(['getEventsCountByMonthDays'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetMonthSuccess()
    {
        $month = '2015-03';

        $calendar = [
            [
                'events' => 6,
                'date' => new \DateTime('2015-03-01'),
            ],
            [
                'events' => 20,
                'date' => new \DateTime('2015-03-02'),
            ],
        ];

        $this->eventRepository->expects($this->once())
            ->method('getEventsCountByMonthDays')
            ->with('2015', '03')
            ->will($this->returnValue($calendar));

        $calendarService = new CalendarService($this->container);
        $calendarService->setEventRepository($this->eventRepository);
        $result = $calendarService->getMonth($month);

        $this->assertEquals($calendar, $result);
    }

    /**
     * @expectedException \Array51\AgendaBundle\Exception\InvalidDateException
     */
    public function testGetMonthInvalidDate()
    {
        $month = '201503';

        $calendarService = new CalendarService($this->container);
        $calendarService->setEventRepository($this->eventRepository);
        $result = $calendarService->getMonth($month);
    }
}
