<?php

namespace Array51\AgendaBundle\Service;

use Array51\DataBundle\Entity\Event;
use Array51\AgendaBundle\Exception\EventNotFoundException;
use Array51\AgendaBundle\Exception\InvalidEventException;
use Array51\DataBundle\Repository\EventRepository;

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
     * @param int $id
     * @return int
     * @throws EventNotFoundException
     * @throws InvalidEventException
     */
    public function save($data, $id = null)
    {
        if (null == $id) {
            $event = new Event();
        } else {
            $event = $this->eventRepository->find($id);

            if (null == $event) {
                throw new EventNotFoundException();
            }
        }

        $this->formService
            ->create('event', $event, ['allow_extra_fields' => true,])
            ->submit($data, false);

        if (!$this->formService->isValid()) {
            $errors = $this->formService->getErrors();
            throw new InvalidEventException($errors);
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
            throw new EventNotFoundException();
        }
    }

    /**
     * @param int $id
     * @throws EventNotFoundException
     */
    public function deleteById($id)
    {
        /** @var Event $event */
        $event = $this->eventRepository->find($id);
        if (null == $event) {
            throw new EventNotFoundException();
        }

        $this->eventRepository->delete($event);
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $filters
     * @return array
     */
    public function getAll($offset = null, $limit = null, array $filters = [])
    {
        return $this->eventRepository->getAll($offset, $limit, $filters);
    }

    /**
     * @param array $filters
     * @return int
     */
    public function countAll(array $filters = [])
    {
        return $this->eventRepository->countAll($filters);
    }
}
