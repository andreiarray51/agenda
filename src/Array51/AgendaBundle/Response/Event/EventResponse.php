<?php

namespace Array51\AgendaBundle\Response\Event;

use JMS\Serializer\Annotation as JMS;

class EventResponse
{
    /**
     * @var int
     *
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    private $description;

    /**
     * @var string
     *
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("due")
     */
    private $due;

    /**
     * @var string
     *
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("createdAt")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @JMS\Type("DateTime")
     * @JMS\SerializedName("updatedAt")
     */
    private $updatedAt;

    /**
     * @param array $event
     */
    public function __construct(array $event)
    {
        $this->setId($event['id']);
        $this->setName($event['name']);
        $this->setDescription($event['description']);
        $this->setDue($event['due']);
        $this->setCreatedAt($event['created_at']);
        $this->setUpdatedAt($event['updated_at']);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = strlen($description) > 0 ? $description : '';
    }

    /**
     * @return \DateTime
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * @param \DateTime $due
     */
    public function setDue($due)
    {
        $this->due = $due;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
