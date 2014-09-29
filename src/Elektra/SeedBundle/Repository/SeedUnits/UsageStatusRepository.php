<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\SeedUnits;

use Elektra\CrudBundle\Repository\Repository;
use Elektra\SeedBundle\Entity\SeedUnits\UsageStatus;

/**
 * Class UsageStatusRepository
 *
 * @package Elektra\SeedBundle\Repository\SeedUnits
 *
 * @version 0.1-dev
 */
// URGENT / CHECK should this also be a crud-repository?
class UsageStatusRepository extends Repository
{

    /**
     * @param string $internalName
     * @return UsageStatus
     */
    public function findByInternalName($internalName)
    {

        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select('uu')
            ->from('ElektraSeedBundle:SeedUnits\UsageStatus', 'uu')
            ->where('uu.internalName = :internalName')
            ->setParameter("internalName", $internalName);

        $result = $builder->getQuery()->getSingleResult();
        return $result;
    }
}