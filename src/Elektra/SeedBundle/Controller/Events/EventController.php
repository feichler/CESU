<?php

namespace Elektra\SeedBundle\Controller\Events;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Events\ShippingEvent;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Symfony\Component\Form\FormInterface;

class EventController extends Controller
{

    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Events', 'Event');
    }
}