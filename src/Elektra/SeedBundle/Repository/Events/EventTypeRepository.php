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
use Elektra\SeedBundle\Entity\Events\EventType;

/**
 * Class EventTypeRepository
 *
 * @package Elektra\SeedBundle\Repository\Events
 *
 * @version 0.1-dev
 */
// URGENT / CHECK should this also be a crud-repository?
class EventTypeRepository extends Repository
{

    /**
     * @param string $internalName
     * @return EventType
     */
    public function findByInternalName($internalName)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $builder->select('et')
            ->from('ElektraSeedBundle:Events\EventType', 'et')
            ->where('et.internalName = :internalName')
            ->setParameter("internalName", $internalName);

        $result = $builder->getQuery()->getSingleResult();
        return $result;
    }
}