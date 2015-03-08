<?php

namespace Array51\AgendaBundle\Response\Error;

use Array51\AgendaBundle\Response\AbstractBaseResponse;

class ErrorResponse extends AbstractBaseResponse
{
    public function __construct()
    {
        $this->setSuccess(false);
    }
}
