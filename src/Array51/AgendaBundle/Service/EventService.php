<?php

namespace Array51\AgendaBundle\Service;

use Array51\DataBundle\Entity\Event;
use Array51\AgendaBundle\Exception\InvalidEventException;
use Array51\DataBundle\Repository\EventRepository;
use Array51\AgendaBundle\Exception\EventNotFoundException;

class EventService extends AbstractBaseService
{
    /**
     * @var FormService
     */
    private $formService;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param FormService $formService
     */
    public function setFormService(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * @param EventRepository $eventRepository
     */
    public function setEventRepository(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param array $data
     * @return int
     * @throws InvalidEventException
     */
    public function save($data)
    {
        $event = new Event();
        $this->formService
            ->create('event', $event)
            ->submit($data);

        if (!$this->formService->isValid()) {
            $errors = $this->formService->getErrors();
            throw new InvalidEventException('Invalid event data', $errors);
        }

        $this->eventRepository->save($event);

        return $event->getId();
    }

    /**
     * @param int $id
     * @return array
     * @throws EventNotFoundException
     */
    public function getById($id)
    {
        try {
            return $this->eventRepository->getById($id);
        } catch (\Exception $e) {
            throw new EventNotFoundException('Event not found');
        }
    }
}
