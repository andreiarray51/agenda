<?php

namespace Array51\DataBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class AbstractEntityRepository extends EntityRepository
{
    /**
     * @param Query $query
     * @return string
     */
    protected function getCacheId(Query $query)
    {
        return md5($query->getSQL());
    }
}
