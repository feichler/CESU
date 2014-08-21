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
use Elektra\SeedBundle\Repositories\CRUDRepositoryInterface;

/**
 * Class RequestCompletionRepository
 *
 * @package Elektra\SeedBundle\Repositories\Requests
 *
 * @version 0.1-dev
 */
class RequestCompletionRepository extends EntityRepository implements CRUDRepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('rc'));
        $builder->from($this->getEntityName(), 'rc');

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
    public function getEntries($page, $perPage, $filters = array(), $ordering = array())
    {

        $entries = $this->findBy($filters, $ordering, $perPage, ($page - 1) * $perPage);

        return $entries;
    }

}