<?php

namespace Array51\AgendaBundle\Response;

use JMS\Serializer\Annotation as JMS;

class AbstractBaseResponse
{
    /**
     * @var bool
     *
     * @JMS\Type("boolean")
     * @JMS\SerializedName("success")
     */
    protected $success = true;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("errorMessage")
     */
    protected $errorMessage;

    /**
     * @var array
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("errors")
     */
    protected $errors;

    /**
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param int $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
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
