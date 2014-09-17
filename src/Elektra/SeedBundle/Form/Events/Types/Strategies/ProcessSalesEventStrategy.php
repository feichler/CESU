<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.09.14
 * Time: 11:35
 */

namespace Elektra\SeedBundle\Form\Events\Types\Strategies;


use Elektra\SeedBundle\Entity\Events\Event;
use Elektra\SeedBundle\Entity\Events\SalesEvent;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;

class ProcessSalesEventStrategy implements ProcessEventStrategyInterface
{
    public function prepare(Event $eventTemplate)
    {
    }

    public function isAllowed(SeedUnit $seedUnit, Event $eventTemplate)
    {
        return $eventTemplate instanceof SalesEvent and ($seedUnit->getSalesStatus() == null or $seedUnit->getSalesStatus()->getId() != $eventTemplate->getSalesStatus()->getId());
    }

    public function process(SeedUnit $seedUnit, Event $event)
    {
        if ($event instanceof SalesEvent)
        {
            $seedUnit->setSalesStatus($event->getSalesStatus());
        }
    }
}