<?php

namespace Elektra\SeedBundle\Repositories\Companies;

use Doctrine\ORM\EntityRepository;

class RegionRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('r'));
        $builder->from($this->getEntityName(), 'r');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}