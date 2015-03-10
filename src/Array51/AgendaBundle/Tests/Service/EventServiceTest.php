<?php

namespace Array51\AgendaBundle\Tests\Service;

use Array51\AgendaBundle\Service\FormService;
use Array51\DataBundle\Repository\EventRepository;
use Array51\AgendaBundle\Service\EventService;
use Array51\DataBundle\Entity\Event;

class EventServiceTest extends AbstractBaseServiceTest
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var FormService
     */
    private $formService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formService = $this->getMockBuilder(
            'Array51\AgendaBundle\Service\FormService'
        )
            ->setMockClassName('FormService')
            ->setMethods(['create', 'submit', 'isValid', 'getErrors'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventRepository = $this->getMockBuilder(
            'Array51\DataBundle\Repository\EventRepository'
        )
            ->setMockClassName('EventRepository')
            ->setMethods(['save', 'getById', 'getAll', 'countAll'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param $data
     * @param $event
     * @param $expected
     * @dataProvider dataSaveSuccess
     */
    public function testSaveNewSuccess($data, $event, $expected)
    {
        $this->formService->expects($this->once())
            ->method('create')
            ->with('event', $event)
            ->will($this->returnValue($this->formService));

        $this->formService->expects($this->once())
            ->method('submit')
            ->with($data)
            ->will($this->returnValue($this->formService));

        $this->formService->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->eventRepository->expects($this->once())
            ->method('save')
            ->with($event);

        $eventService = new EventService($this->container);
        $eventService->setFormService($this->formService);
        $eventService->setEventRepository($this->eventRepository);
        $eventId = $eventService->save($data);

        $this->assertEquals($expected, $eventId);
    }

    /**
     * @param $data
     * @param $event
     * @expectedException \Array51\AgendaBundle\Exception\InvalidEventException
     * @dataProvider dataSaveFail
     */
    public function testSaveNewFail($data, $event, $errors)
    {
        $this->formService->expects($this->once())
            ->method('create')
            ->with('event', $event)
            ->will($this->returnValue($this->formService));

        $this->formService->expects($this->once())
            ->method('submit')
            ->with($data)
            ->will($this->returnValue($this->formService));

        $this->formService->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formService->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue([]));

        $eventService = new EventService($this->container);
        $eventService->setFormService($this->formService);
        $eventService->setEventRepository($this->eventRepository);
        $eventService->save($data);
    }

    /**
     * @param array $event
     * @param array $expected
     * @dataProvider dataGetById
     */
    public function testGetByIdSuccess($event, $expected)
    {
        $this->eventRepository->expects($this->once())
            ->method('getById')
            ->with($event['id'])
            ->will($this->returnValue($event));

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $result = $eventService->getById($event['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * @param array $event
     * @param array $expected
     * @dataProvider dataGetById
     */
    public function testGetByIdNotFound($event, $expected)
    {
        $this->eventRepository->expects($this->once())
            ->method('getById')
            ->with($event['id'])
            ->will($this->returnValue($event));

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $result = $eventService->getById($event['id']);

        $this->assertEquals($expected, $result);
    }

    /**
     * @param array $data
     * @param array $expected
     * @dataProvider dataGetAll
     */
    public function testGetAll($data, $expected)
    {
        $this->eventRepository->expects($this->once())
            ->method('getAll')
            ->with($data['offset'], $data['limit'], $data['filters'])
            ->will($this->returnValue($data['events']));

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $result = $eventService->getAll(
            $data['offset'],
            $data['limit'],
            $data['filters']
        );

        $this->assertEquals($expected, $result);
    }

    public function testCountAll()
    {
        $total = 10;

        $this->eventRepository->expects($this->once())
            ->method('countAll')
            ->with([])
            ->will($this->returnValue($total));

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $result = $eventService->countAll();

        $this->assertEquals($total, $result);
        $this->assertInternalType('integer', $result);
    }

    /**
     * @return array
     */
    public function dataSaveSuccess()
    {
        $data = [
            'id' => 1,
            'name' => 'New event',
            'description' => 'New event description text',
            'due' => '2015-03-08',
        ];

        $event = new Event($data);

        $expected = $event->getId(1);

        return [
            [
                $data,
                $event,
                $expected,
            ]
        ];
    }

    /**
     * @return array
     */
    public function dataSaveFail()
    {
        $data = [
            'description' => 'New event description text',
            'due' => '2015-03-08',
        ];

        $event = new Event($data);

        $errors = [
            'property' => 'message',
        ];

        return [
            [
                $data,
                $event,
                $errors,
            ]
        ];
    }

    /**
     * @return array
     */
    public function dataGetById()
    {
        $event = [
            'id' => 1,
            'name' => 'New event',
            'description' => 'New event description text',
            'due' => new \DateTime('2015-03-08'),
        ];

        $expected = $event;

        return [
            [
                $event,
                $expected,
            ]
        ];
    }

    /**
     * @return array
     */
    public function dataGetAll()
    {
        $data1 = [
            'offset' => 0,
            'limit' => 10,
            'filters' => [
                'due' => '2015-03-10',
            ],
            'events' => [
                [
                    'id' => 'Event name',
                    'due' => new \DateTime('2015-03-10'),
                ],
                [
                    'id' => 'Other Event name',
                    'due' => new \DateTime('2015-03-10'),
                ],
            ],
        ];
        $expected1 = $data1['events'];

        $data2 = [
            'offset' => 0,
            'limit' => 0,
            'filters' => [],
            'events' => [],
        ];
        $expected2 = $data2['events'];

        return [
            [
                $data1,
                $expected1,
            ],
            [
                $data2,
                $expected2,
            ],
        ];
    }
}
