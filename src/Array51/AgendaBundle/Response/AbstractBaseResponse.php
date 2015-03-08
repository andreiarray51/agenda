<?php

namespace Array51\AgendaBundle\Response;

use JMS\Serializer\Annotation as JMS;
use FOS\RestBundle\Util\Codes;

class AbstractBaseResponse
{
    /**
     * @var int
     *
     * @JMS\Exclude
     */
    protected $httpCode = Codes::HTTP_OK;

    /**
     * @var string
     *)
     * @JMS\SerializedName("message")
     */
    protected $message = '';

    /**
     * @var array
    ")
     * @JMS\SerializedName("errors")
     */
    protected $errors = array();

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param int $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
}
