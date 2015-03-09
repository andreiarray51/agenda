<?php

namespace Array51\DataBundle\Repository;

use Array51\DataBundle\Entity\Event;

class EventRepository extends AbstractEntityRepository
{
    /**
     * @param Event $event
     */
    public function save(Event $event)
    {
        $em = $this->getEntityManager();
        $em->persist($event);
        $em->flush();
    }

    /**
     * @param int $id
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getById($id)
    {
        $query = $this->createQueryBuilder('e')
            ->select(
                'e.id',
                'e.name',
                'e.description',
                'e.due',
                'e.createdAt AS created_at',
                'e.updatedAt AS updated_at'
            )
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $query->useQueryCache(true)
            ->useResultCache(true, $this->getCacheId($query));

        return $query->getSingleResult();
    }
}
