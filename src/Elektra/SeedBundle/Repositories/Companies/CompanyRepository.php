<?php

namespace Elektra\SeedBundle\Repositories\Companies;

use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('c'));
        $builder->from($this->getEntityName(), 'c');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}