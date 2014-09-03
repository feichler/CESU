<?php

namespace Elektra\SeedBundle\Definition\Events;

use Elektra\CrudBundle\Crud\Definition;

class EventDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Events', 'Event');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('event');

        // has valid parents
        $this->addParent('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
    }
}