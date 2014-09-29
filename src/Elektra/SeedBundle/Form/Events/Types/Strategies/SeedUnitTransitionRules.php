<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.09.14
 * Time: 11:33
 */

namespace Elektra\SeedBundle\Form\Events\Types\Strategies;


use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Entity\SeedUnits\ShippingStatus;

class SeedUnitTransitionRules
{
    /** @var array */
    private $allowedStatuses;

    function __construct(Event $eventTemplate)
    {
        $newStatus = $eventTemplate->getShippingStatus() != null ? $eventTemplate->getShippingStatus()->getInternalName() : null;
        $this->allowedStatuses = ($newStatus != null and isset(ShippingStatus::$ALLOWED_TO[$newStatus])) ? ShippingStatus::$ALLOWED_TO[$newStatus] : array();
    }

    public function checkNewShippingStatus(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $eventTemplate->getShippingStatus() == null or in_array($seedUnit->getShippingStatus()->getInternalName(), $this->allowedStatuses);
    }

    public function checkNewSalesStatus(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $seedUnit->getSalesStatus() == null or $eventTemplate->getSalesStatus() == null or $seedUnit->getSalesStatus()->getId() != $eventTemplate->getSalesStatus()->getId();
    }

    public function checkNewUsageStatus(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $seedUnit->getUsageStatus() == null or $eventTemplate->getUsageStatus() == null or $seedUnit->getUsageStatus()->getId() != $eventTemplate->getUsageStatus()->getId();
    }
}