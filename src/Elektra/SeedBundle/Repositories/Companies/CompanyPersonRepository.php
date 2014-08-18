<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories\Companies;


/**
 * Class CompanyPersonRepository
 *
 * @package Elektra\SeedBundle\Repositories\Companies
 *
 *          @version 0.1-dev
 */
class CompanyPersonRepository extends PersonRepository
{
    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('cp'));
        $builder->from($this->getEntityName(), 'cp');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
    /**
     * {@inheritdoc}
     */
    public function getEntries($page, $perPage)
    {

        $entries = $this->findBy(array(), array(), $perPage, ($page - 1) * $perPage);

        return $entries;
    }
}