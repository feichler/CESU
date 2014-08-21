<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class CRUDRepository
 *
 * @package Elektra\SeedBundle\Repositories
 *
 * @version 0.1-dev
 */
class CRUDRepository extends EntityRepository implements CRUDRepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCount($filters = array())
    {

        // ENHANCE find a better method to count the entries with filters! this method executes the complete query instead of SELECT COUNT(*)

        $filterCriteria = Criteria::create();

        foreach ($filters as $key => $value) {
            $filterCriteria->andWhere(Criteria::expr()->eq($key, $value));
        }

        $count = $this->matching($filterCriteria)->count();

        return $count;
    }

    /**
     * Checks if an entry can be deleted (no references, no constraint violations, etc)
     *
     * @param mixed $entry
     *
     * @return bool
     */
    public function getCanDelete($entry)
    {

        // URGENT: Implement getCanDelete() method.
        
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntries($page, $perPage, $filters = array(), $ordering = array())
    {

        $entries = $this->findBy($filters, $ordering, $perPage, ($page - 1) * $perPage);

        return $entries;
    }
}