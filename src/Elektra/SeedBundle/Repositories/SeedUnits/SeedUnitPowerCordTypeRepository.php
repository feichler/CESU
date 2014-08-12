<?php

namespace Elektra\SeedBundle\Repositories\SeedUnits;

use Doctrine\ORM\EntityRepository;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType;

class SeedUnitPowerCordTypeRepository extends EntityRepository
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
     *
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

    public function getEntries($page, $perPage)
    {

        $entries = $this->findBy(array(), array(), $perPage, ($page - 1) * $perPage);

        return $entries;
    }
}