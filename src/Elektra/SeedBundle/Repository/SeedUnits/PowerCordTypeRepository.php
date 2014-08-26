<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\SeedUnits;

use Elektra\CrudBundle\Repository\Repository as CrudRepository;

/**
 * Class PowerCordTypeRepository
 *
 * @package Elektra\SeedBundle\Repository\SeedUnits
 *
 * @version 0.1-dev
 */
class PowerCordTypeRepository extends CrudRepository
{

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getCount($filters = array())
    //    {
    //
    //        $builder = $this->getEntityManager()->createQueryBuilder();
    //        $builder->select($builder->expr()->count('pct'));
    //        $builder->from($this->getEntityName(), 'pct');
    //
    //        $query = $builder->getQuery();
    //
    //        return $query->getSingleScalarResult();
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getCanDelete($powerCordType)
    //    {
    //
    //        $builder = $this->getEntityManager()->createQueryBuilder();
    //        $builder->select($builder->expr()->count('su'));
    //        $builder->where("su.powerCordTypeId = :pctId");
    //        $builder->from("Elektra\\SeedBundle\\Entity\\SeedUnits\\SeedUnit", "su");
    //        $builder->setParameter("pctId", $powerCordType->getId());
    //
    //        return $builder->getQuery()->getSingleScalarResult() == 0;
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getEntries($page, $perPage, $filters = array(), $ordering = array())
    //    {
    //
    //        $entries = $this->findBy($filters, $ordering, $perPage, ($page - 1) * $perPage);
    //
    //        return $entries;
    //    }

}