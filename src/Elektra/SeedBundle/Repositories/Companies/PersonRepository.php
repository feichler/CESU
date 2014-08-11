<?php

namespace Elektra\SeedBundle\Repositories\Companies;

use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{

    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('p'));
        $builder->from($this->getEntityName(), 'p');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getEntries($page, $perPage)
    {

        $entries = $this->findBy(array(), array(), $perPage, ($page - 1) * $perPage);

        return $entries;
    }
}