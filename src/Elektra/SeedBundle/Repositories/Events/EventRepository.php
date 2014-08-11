<?php

namespace Elektra\SeedBundle\Repositories\Events;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('e'));
        $builder->from($this->getEntityName(), 'e');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}