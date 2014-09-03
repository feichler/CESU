<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\Events;

use Doctrine\ORM\EntityRepository;

/**
 * Class UnitStatusRepository
 *
 * @package Elektra\SeedBundle\Repository\Events
 *
 * @version 0.1-dev
 */
// URGENT / CHECK should this also be a crud-repository?
class UnitStatusRepository extends EntityRepository
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('us'));
        $builder->from($this->getEntityName(), 'us');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}