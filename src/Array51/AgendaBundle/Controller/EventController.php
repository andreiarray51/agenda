<?php

namespace Array51\AgendaBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use Array51\AgendaBundle\Service\EventService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Array51\AgendaBundle\Response\Event\GetResponse;
use Array51\AgendaBundle\Response\Event\CreateResponse;
use Array51\AgendaBundle\Response\Event\UpdateResponse;
use Array51\AgendaBundle\Response\Event\ListResponse;

class EventController extends Controller
{
    /**
     * @var EventService
     */
    private $eventService;

    /**
     * @param EventService $eventService
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Get event by id",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "required"="true"}
     *  },
     * statusCodes={
     *      200="Returned when successful retrieved event",
     *      404={
     *          "Returned when event not found",
     *          "Returned when bad event id format sent"
     *    }
     *  }
     * )
     *
     * @View(statusCode=200)
     *
     * @param int $id
     * @return GetResponse
     */
    public function getAction($id)
    {
        $event = $this->eventService->getById($id);

        return new GetResponse($event);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Create new event",
     *  parameters={
     *      {"name"="name", "dataType"="text", "required"="true"},
     *      {"name"="description", "dataType"="text", "required"="false"},
     *      {
     *          "name"="due",
     *          "dataType"="text",
     *          "required"="true",
     *          "format"="Y-m-d",
     *          "description"="Date event is due at"
     *      }
     *  },
     * statusCodes={
     *      201="Returned when successful created event",
     *      400={
     *          "Returned when invalid parameters sent",
     *          "Returned when invalid json sent"
     *    }
     *  }
     * )
     *
     * @View(statusCode=201)
     *
     * @param Request $request
     * @return CreateResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $eventId = $this->eventService->save($data);
        $event = $this->eventService->getById($eventId);

        return new CreateResponse($event);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Update event",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "required"="true"}
     *  },
     *  parameters={
     *      {"name"="name", "dataType"="text", "required"="false"},
     *      {"name"="description", "dataType"="text", "required"="false"},
     *      {
     *          "name"="due",
     *          "dataType"="text",
     *          "required"="true",
     *          "format"="Y-m-d",
     *          "description"="Date event is due at"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful updated event",
     *      400={
     *          "Returned when invalid parameters sent",
     *          "Returned when invalid json sent",
     *      },
     *      404="Returned when event not found",
     *  }
     * )
     *
     * @View(statusCode=200)
     *
     * @param Request $request
     * @param int $id
     * @return CreateResponse
     */
    public function updateAction(Request $request, $id)
    {
        $data = $request->request->all();
        $eventId = $this->eventService->save($data, $id);
        $event = $this->eventService->getById($eventId);

        return new UpdateResponse($event);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Get events list ordered by due and created date",
     *  parameters={
     *      {
     *          "name"="due",
     *          "dataType"="text",
     *          "required"="false",
     *          "format"="Y-m-d",
     *          "description"="Date event is due at"
     *      },
     *      {
     *          "name"="offset",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="Position to retrieve events from"
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="Number of events to retrieve"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful retrieved event"
     *  }
     * )
     *
     * @View(statusCode=200)
     *
     * @param Request $request
     * @return ListResponse
     */
    public function listAction(Request $request)
    {
        $offset = $request->get('offset');
        $limit = $request->get('offset');
        $filters = [
            'due' => $request->get('due'),
        ];

        $events = $this->eventService->getAll($offset, $limit, $filters);
        $total = $this->eventService->countAll($filters);

        return new ListResponse($events, $total);
    }
}
