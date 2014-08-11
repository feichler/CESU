<?php

namespace Elektra\SeedBundle\Repositories\SeedUnits;

use Doctrine\ORM\EntityRepository;
use Elektra\SeedBundle\Entity\SeedUnits\PowerCordType;

class PowerCordTypeRepository extends EntityRepository
{
    public function getCount()
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('pct'));
        $builder->from($this->getEntityName(), 'pct');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * @param PowerCordType $powerCordType
     * @return bool
     */
    public function getCanDelete($powerCordType)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('su'));
        $builder->where("su.powerCordTypeId = :pctId");
        $builder->from("Elektra\\SeedBundle\\Entity\\SeedUnits\\SeedUnit", "su");
        $builder->setParameter("pctId", $powerCordType->getId());

        return $builder->getQuery()->getSingleScalarResult() == 0;
    }
}