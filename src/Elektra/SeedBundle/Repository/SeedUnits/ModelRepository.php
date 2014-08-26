<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\SeedUnits;

use Elektra\CrudBundle\Repository\Repository as CrudRepository;

/**
 * Class ModelRepository
 *
 * @package Elektra\SeedBundle\Repository\SeedUnits
 *
 * @version 0.1-dev
 */
class ModelRepository extends CrudRepository
{


    /**
     * {@inheritdoc}
     */
    public function getCanDelete($model)
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select($builder->expr()->count('su'));
        $builder->where("su.modelId = :sumId");
        $builder->from("Elektra\\SeedBundle\\Entity\\SeedUnits\\SeedUnit", "su");
        $builder->setParameter("sumId", $model->getId());

        return $builder->getQuery()->getSingleScalarResult() == 0;
    }

}