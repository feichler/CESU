<?php

namespace Elektra\SeedBundle\Repositories\SeedUnits;

use Doctrine\ORM\EntityRepository;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;

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

    /**
     * @param SeedUnitModel $model
     * @return bool
     */
    public function getCanDelete($model)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('su'));
        $builder->where("su.seedUnitModelId = :sumId");
        $builder->from("Elektra\\SeedBundle\\Entity\\SeedUnits\\SeedUnit", "su");
        $builder->setParameter("sumId", $model->getId());

        return $builder->getQuery()->getSingleScalarResult() == 0;
    }

    public function getEntries($page, $perPage)
    {
        $entries = $this->findBy(array(), array(), $perPage, ($page - 1) * $perPage);
        return $entries;
    }
}