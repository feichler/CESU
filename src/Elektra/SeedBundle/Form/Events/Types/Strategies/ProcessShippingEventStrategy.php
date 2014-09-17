<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.09.14
 * Time: 11:35
 */

namespace Elektra\SeedBundle\Form\Events\Types\Strategies;


use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\Events\StatusEvent;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

class ProcessShippingEventStrategy implements ProcessEventStrategyInterface
{
    private $allowedStatuses;

    public function prepare(Event $eventTemplate)
    {
        $newStatus = $eventTemplate instanceof StatusEvent ? $eventTemplate->getUnitStatus()->getInternalName() : null;
        $this->allowedStatuses = $newStatus != null and isset(UnitStatus::$ALLOWED_TO[$newStatus]) ? UnitStatus::$ALLOWED_TO[$newStatus] : array();
    }

    public function isAllowed(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return in_array($seedUnit->getShippingStatus()->getInternalName(), $this->allowedStatuses);
    }

    public function process(SeedUnit $seedUnit, Event $event)
    {
        if ($event instanceof StatusEvent)
        {
            $seedUnit->setShippingStatus($event->getUnitStatus());
            if ($event instanceof ShippingEvent)
            {
                $seedUnit->setLocation($event->getLocation());
            }
        }
    }
}