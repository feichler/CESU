<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 15.09.14
 * Time: 16:32
 */

namespace Elektra\SeedBundle\Form;


use Elektra\SeedBundle\Entity\Events\UnitStatus;

class FormsHelper {

    /**
     * @param array $seedUnits
     * @return array List containing the internal names of the allowed statuses
     */
    public static function getAllowedStatuses(array $seedUnits)
    {

        $statuses = array();
        foreach($seedUnits as $seedUnit) {
            $allowed = UnitStatus::$ALLOWED_FROM[$seedUnit->getShippingStatus()->getInternalName()];
            $statuses = array_merge($statuses, $allowed);

        }

        $statuses = array_unique($statuses);

        return $statuses;
    }
}