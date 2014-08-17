<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories\Companies;

use Doctrine\ORM\EntityRepository;
use Elektra\SeedBundle\Repositories\CRUDRepositoryInterface;

/**
 * Class PartnerTierRepository
 *
 * @package Elektra\SeedBundle\Repositories\Companies
 *
 * @version 0.1-dev
 */
class PartnerTierRepository extends EntityRepository implements CRUDRepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('pt'));
        $builder->from($this->getEntityName(), 'pt');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getCanDelete($entry)
    {
        // URGENT: Implement getCanDelete() method.
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