<?php

namespace Elektra\SeedBundle\Definition\Events;

use Elektra\CrudBundle\Crud\Definition;

class UnitUsageDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Events', 'UnitUsage');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('unitUsage');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('unitUsages');
    }
}