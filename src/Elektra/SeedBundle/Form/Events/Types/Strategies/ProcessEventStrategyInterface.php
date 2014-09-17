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

interface ProcessEventStrategyInterface
{
    public function prepare(Event $eventTemplate);
    public function isAllowed(SeedUnit $seedUnit, Event $eventTemplate);
    public function process(SeedUnit $seedUnit, Event $event);
} 