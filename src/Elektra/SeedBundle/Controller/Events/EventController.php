<?php

namespace Elektra\SeedBundle\Controller\Events;

use Elektra\CrudBundle\Controller\Controller;

class EventController extends Controller
{

    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Events', 'Event');
    }
}