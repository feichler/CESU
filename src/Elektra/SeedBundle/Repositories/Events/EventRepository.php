<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories\Events;

use Doctrine\ORM\EntityRepository;

/**
 * Class EventRepository
 *
 * @package Elektra\SeedBundle\Repositories\Events
 *
 * @version 0.1-dev
 */
class EventRepository extends EntityRepository
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('e'));
        $builder->from($this->getEntityName(), 'e');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}