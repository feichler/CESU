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
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Elektra\SeedBundle\Repositories\CRUDRepositoryInterface;

/**
 * Class SeedUnitModelRepository
 *
 * @package Elektra\SeedBundle\Repositories\SeedUnits
 *
 * @version 0.1-dev
 */
class SeedUnitModelRepository extends EntityRepository implements CRUDRepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getCount()
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('m'));
        $builder->from($this->getEntityName(), 'm');

        $query = $builder->getQuery();

        return $query->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function getCanDelete($model)
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('su'));
        $builder->where("su.seedUnitModelId = :sumId");
        $builder->from("Elektra\\SeedBundle\\Entity\\SeedUnits\\SeedUnit", "su");
        $builder->setParameter("sumId", $model->getId());

        return $builder->getQuery()->getSingleScalarResult() == 0;
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