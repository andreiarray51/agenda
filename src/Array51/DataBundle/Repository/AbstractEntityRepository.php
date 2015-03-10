<?php

namespace Array51\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

class AbstractEntityRepository extends EntityRepository
{
    /**
     * @param QueryBuilder $query
     * @param array $filters
     */
    protected function applyFilters(QueryBuilder $query, array $filters)
    {
        $alias = $query->getRootAliases()[0];
        array_walk(
            $filters,
            function ($value, $key) use ($query, $alias) {
                if ($value) {
                    $query->andWhere(sprintf('%s.%s = :%s', $alias, $key, $key))
                        ->setParameter($key, $value);
                }
            }
        );
    }

    /**
     * @param Query $query
     * @return string
     */
    protected function getCacheId(Query $query)
    {
        return md5($query->getSQL().serialize($query->getParameters()));
    }
}
