<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\Companies;

use Elektra\CrudBundle\Repository\Repository as CrudRepository;

/**
 * Class RegionRepository
 *
 * @package Elektra\SeedBundle\Repository\Companies
 *
 * @version 0.1-dev
 */
class RegionRepository extends CrudRepository
{

    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getCount()
    //    {
    //
    //        $builder = $this->getEntityManager()->createQueryBuilder();
    //        $builder->select($builder->expr()->count('r'));
    //        $builder->from($this->getEntityName(), 'r');
    //
    //        $query = $builder->getQuery();
    //
    //        return $query->getSingleScalarResult();
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getCanDelete($entry)
    //    {
    //        // URGENT: Implement getCanDelete() method.
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