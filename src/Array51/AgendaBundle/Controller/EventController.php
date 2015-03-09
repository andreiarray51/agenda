<?php

namespace Array51\AgendaBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use Array51\AgendaBundle\Service\EventService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Array51\AgendaBundle\Response\Event\GetResponse;
use Array51\AgendaBundle\Response\Event\CreateResponse;

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
     * @return \FOS\RestBundle\View\View
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
     * @return \FOS\RestBundle\View\View
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $eventId = $this->eventService->save($data);
        $event = $this->eventService->getById($eventId);

        return new CreateResponse($event);
    }
}
