<?php

namespace Array51\AgendaBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use Array51\AgendaBundle\Response\AbstractBaseResponse;
use FOS\RestBundle\View\View;

abstract class AbstractBaseController extends Controller
{
    /**
     * @param AbstractBaseResponse $response
     * @return View
     */
    protected function response(AbstractBaseResponse $response)
    {
        $view = View::create();
        $view->setData($response);
        $view->setStatusCode($response->getHttpCode());

        return $view;
    }
}
