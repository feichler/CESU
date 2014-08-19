<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repositories\Requests;

use Doctrine\ORM\EntityRepository;

/**
 * Class RequestRepository
 *
 * @package Elektra\SeedBundle\Repositories\Requests
 *
 * @version 0.1-dev
 */
class RequestRepository extends EntityRepository
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('r'));
        $builder->from($this->getEntityName(), 'r');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }
}