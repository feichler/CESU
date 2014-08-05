<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\EntityRepository;

class SeedUnitRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('s'));
        $builder->from($this->getEntityName(), 's');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}