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

    public function getEntries($page, $perPage)
    {

        $entries = $this->findBy(array(), array(), $perPage, ($page - 1) * $perPage);

        return $entries;
    }
}