<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.09.14
 * Time: 11:35
 */

namespace Elektra\SeedBundle\Form\Events\Types\Strategies;


use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Events\PartnerEvent;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

class ProcessUsageEventStrategy implements ProcessEventStrategyInterface
{
    public function prepare(Event $eventTemplate)
    {
    }

    public function isAllowed(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $eventTemplate instanceof PartnerEvent and ($seedUnit->getUnitUsage() == null or $seedUnit->getUnitUsage()->getId() != $eventTemplate->getUsage()->getId());
    }

    public function process(SeedUnit $seedUnit, Event $event)
    {
        if ($event instanceof PartnerEvent)
        {
            $seedUnit->setUnitUsage($event->getUsage());
        }
    }
}