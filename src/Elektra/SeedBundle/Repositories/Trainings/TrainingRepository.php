<?php

namespace Elektra\SeedBundle\Repositories\Trainings;

use Doctrine\ORM\EntityRepository;

class TrainingRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('t'));
        $builder->from($this->getEntityName(), 't');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}