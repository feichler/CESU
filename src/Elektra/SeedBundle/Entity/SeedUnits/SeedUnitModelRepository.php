<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\EntityRepository;

class SeedUnitModelRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('m'));
        $builder->from($this->getEntityName(), 'm');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}