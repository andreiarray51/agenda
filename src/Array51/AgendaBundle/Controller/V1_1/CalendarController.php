<?php

namespace Array51\AgendaBundle\Controller\V1_1;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use Array51\AgendaBundle\Controller\TokenAuthenticatedControllerInterface;
use Array51\AgendaBundle\Service\CalendarService;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Array51\AgendaBundle\Response\V1_1\Calendar\CalendarResponse;

class CalendarController extends Controller implements TokenAuthenticatedControllerInterface
{
    /**
     * @var CalendarService
     */
    private $calendarService;

    /**
     * @param CalendarService $calendarService
     */
    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     *
     * Since Version 1.1
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get events count for each day of a month",
     *  requirements={
     *      {
     *          "name"="v",
     *          "dataType"="string",
     *          "required"="true",
     *          "default"="1.1",
     *          "description"="API Version"
     *      },
     *      {
     *          "name"="token",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="Access token"
     *      }
     *  },
     *  parameters={
     *      {
     *          "name"="month",
     *          "dataType"="text",
     *          "required"="true",
     *          "format"="YYYY-mm"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful retrieved events list",
     *      403="Returned when invalid access token",
     *      404="Returned when invalid parameters sent"
     *  }
     * )
     *
     * @View(statusCode=200)
     *
     * @param Request $request
     * @return CalendarResponse
     */
    public function calendarAction(Request $request)
    {
        $month = $request->get('month');
        $events = $this->calendarService->getMonth($month);

        return new CalendarResponse($events);
    }
}
