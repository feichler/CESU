<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Repository\Companies;
use Elektra\SeedBundle\Entity\Companies\GenericLocation;

/**
 * Class GenericLocationRepository
 *
 * @package Elektra\SeedBundle\Repository\Companies
 *
 * @version 0.1-dev
 */
class GenericLocationRepository extends PhysicalLocationRepository
{
    /**
     * @param string $internalName
     * @return GenericLocation
     */
    public function findByInternalName($internalName)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select('gl')
            ->from('ElektraSeedBundle:Companies\GenericLocation', 'gl')
            ->where('gl.internalName = :internalName')
            ->setParameter("internalName", $internalName);

        $result = $builder->getQuery()->getSingleResult();
        return $result;
    }
}