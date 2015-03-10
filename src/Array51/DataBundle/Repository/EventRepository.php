<?php

namespace Array51\DataBundle\Repository;

use Array51\DataBundle\Entity\Event;
use Doctrine\ORM\Query;

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

    /**
     * @param int|null $offset
     * @param int|null $limit
     * @param array $filters
     * @return array
     */
    public function getAll($offset = null, $limit = null, array $filters = [])
    {
        $query = $this->createQueryBuilder('e')
            ->select(
                'e.id',
                'e.name',
                'e.description',
                'e.due'
            )
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('e.due', 'asc')
            ->addOrderBy('e.createdAt', 'desc');

        $this->applyFilters($query, $filters);

        $query = $query->getQuery();

        $query->useQueryCache(true)
            ->useResultCache(true, $this->getCacheId($query));

        return $query->getResult();
    }

    /**
     * @param array $filters
     * @return int
     */
    public function countAll(array $filters = [])
    {
        $query = $this->createQueryBuilder('e')
            ->select('COUNT(e.id)');

        $this->applyFilters($query, $filters);

        $query = $query->getQuery();

        $query->useQueryCache(true)
            ->useResultCache(true, $this->getCacheId($query));

        return $query->getSingleScalarResult();
    }
}
