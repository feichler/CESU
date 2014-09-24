<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.09.14
 * Time: 11:33
 */

namespace Elektra\SeedBundle\Form\Events\Types\Strategies;


use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

class SeedUnitTransitionRules
{
    /** @var array */
    private $allowedStatuses;

    function __construct(Event $eventTemplate)
    {
        $newStatus = $eventTemplate->getUnitStatus() != null ? $eventTemplate->getUnitStatus()->getInternalName() : null;
        $this->allowedStatuses = ($newStatus != null and isset(UnitStatus::$ALLOWED_TO[$newStatus])) ? UnitStatus::$ALLOWED_TO[$newStatus] : array();
    }

    public function checkNewShippingStatus(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $eventTemplate->getUnitStatus() == null or in_array($seedUnit->getShippingStatus()->getInternalName(), $this->allowedStatuses);
    }

    public function checkNewSalesStatus(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $seedUnit->getSalesStatus() == null or $seedUnit->getSalesStatus()->getId() != $eventTemplate->getSalesStatus()->getId();
    }

    public function checkNewUsage(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $seedUnit->getUnitUsage() == null or $seedUnit->getUnitUsage()->getId() != $eventTemplate->getUsage()->getId();
    }
}