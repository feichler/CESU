<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories\SeedUnits;

use Doctrine\ORM\EntityRepository;
use Elektra\SeedBundle\Repositories\CRUDRepositoryInterface;

/**
 * Class SeedUnitRepository
 *
 * @package Elektra\SeedBundle\Repositories\SeedUnits
 *
 * @version 0.1-dev
 */
class SeedUnitRepository extends EntityRepository implements CRUDRepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('s'));
        $builder->from($this->getEntityName(), 's');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
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
        // URGENT Implement getCanDelete() method.
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