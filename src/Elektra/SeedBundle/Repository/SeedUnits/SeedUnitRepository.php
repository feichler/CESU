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
 * Class SeedUnitRepository
 *
 * @package Elektra\SeedBundle\Repository\SeedUnits
 *
 * @version 0.1-dev
 */
class SeedUnitRepository extends CrudRepository
{

    /**
     * {@inheritdoc}
     */
    //    public function getCount($filters = array())
    //    {
    //
    ////        $criteria = Criteria::create();
    ////        foreach ($filters as $key => $filter) {
    ////            $criteria->andWhere(Criteria::expr()->eq($key, $filter));
    //////            $criteria->where();
    ////        }
    ////
    ////        $count = $this->matching($criteria)->count();
    ////        return $count;
    ////        echo $count;
    //        //        $query = $this->createQueryBuilder('a');
    //        //        $query->fi
    //        //        $criteria = Criteria::create();
    //        //        $criteria->andWhere()
    //        //
    //        //$test = $this->matching($filters);
    //        //        echo count($test);
    //        $builder = $this->getEntityManager()->createQueryBuilder();
    //        $builder->select($builder->expr()->count('s'));
    //        $builder->from($this->getEntityName(), 's');
    //        foreach($filters as $key => $value) {
    //            $builder->andWhere($builder->expr()->eq($key,$value));
    //        }
    //
    //        $query = $builder->getQuery();
    //
    //        return $query->getSingleScalarResult();
    //    }

    //    /**
    //     * Checks if an entry can be deleted (no references, no constraint violations, etc)
    //     *
    //     * @param mixed $entry
    //     *
    //     * @return bool
    //     */
    //    public function getCanDelete($entry)
    //    {
    //        // URGENT: Implement getCanDelete() method.
    //    }

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