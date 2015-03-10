<?php

namespace Array51\AgendaBundle\Tests\Service;

use Array51\AgendaBundle\Service\FormService;
use Array51\DataBundle\Repository\EventRepository;
use Array51\AgendaBundle\Service\EventService;
use Array51\DataBundle\Entity\Event;
use Array51\AgendaBundle\Exception\EventNotFoundException;

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
            ->setMethods(['save', 'getById', 'getAll', 'countAll', 'find', 'delete'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param $data
     * @param $event
     * @dataProvider dataSaveSuccess
     */
    public function testSaveNewSuccess($data, $event)
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
        $eventService->save($data);
    }

    /**
     * @param $data
     * @param $event
     * @expectedException \Array51\AgendaBundle\Exception\InvalidEventException
     * @dataProvider dataSaveFail
     */
    public function testSaveNewFail($data, $event)
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
     * @param int $eventId
     * @param array $data
     * @param array $event
     * @dataProvider dataUpdateSuccess
     */
    public function testUpdateSuccess($eventId, $data, $event)
    {
        $this->eventRepository->expects($this->once())
            ->method('find')
            ->with($eventId)
            ->will($this->returnValue($event));

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
        $eventService->save($data, $eventId);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testUpdateNotFound()
    {
        $eventId = 1;
        $data = [];

        $this->eventRepository->expects($this->once())
            ->method('find')
            ->with($eventId)
            ->will($this->returnValue(null));

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $eventService->save($data, $eventId);
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

    public function testDeleteByIdSuccess()
    {
        $eventId = 1;
        $event = new Event();

        $this->eventRepository->expects($this->once())
            ->method('find')
            ->with($eventId)
            ->will($this->returnValue($event));

        $this->eventRepository->expects($this->once())
            ->method('delete')
            ->with($event);

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $eventService->deleteById($eventId);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws EventNotFoundException
     */
    public function testDeleteByIdNotFound()
    {
        $eventId = 1;
        $event = null;

        $this->eventRepository->expects($this->once())
            ->method('find')
            ->with($eventId);

        $eventService = new EventService($this->container);
        $eventService->setEventRepository($this->eventRepository);
        $eventService->deleteById($eventId);
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

        $event = new Event();

        return [
            [
                $data,
                $event,
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

        $event = new Event();

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
    public function dataUpdateSuccess()
    {
        $eventId = 1;

        $data = [
            'name' => 'New event',
            'description' => 'New event description text',
            'due' => '2015-03-08',
        ];

        $event = new Event();
        $event->setName('Old name');
        $event->setDescription('Old description');
        $event->setDue(new \DateTime('2015-03-08'));

        return [
            [
                $eventId,
                $data,
                $event,
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
