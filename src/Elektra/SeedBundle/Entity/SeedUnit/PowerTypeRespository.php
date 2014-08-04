<?php

namespace Elektra\SeedBundle\Entity\SeedUnit;

use Doctrine\ORM\EntityRepository;

class PowertypeRepository extends EntityRepository
{

    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('pt'));
        $builder->from($this->getEntityName(), 'pt');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}