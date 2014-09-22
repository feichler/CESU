<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\Events;

use Elektra\CrudBundle\Repository\Repository;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;

/**
 * Class UnitUsageRepository
 *
 * @package Elektra\SeedBundle\Repository\Events
 *
 * @version 0.1-dev
 */
// URGENT / CHECK should this also be a crud-repository?
class UnitUsageRepository extends Repository
{

    /**
     * @param string $internalName
     * @return UnitUsage
     */
    public function findByInternalName($internalName)
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select('uu')
            ->from('ElektraSeedBundle:Events\UnitUsage', 'uu')
            ->where('uu.internalName = :internalName')
            ->setParameter("internalName", $internalName);

        $result = $builder->getQuery()->getSingleResult();
        return $result;
    }
}