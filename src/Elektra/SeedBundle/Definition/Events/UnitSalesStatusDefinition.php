<?php

namespace Elektra\SeedBundle\Definition\Events;

use Elektra\CrudBundle\Crud\Definition;

class UnitSalesStatusDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Events', 'UnitSalesStatus');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('salesStatus');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('salesStatuses');
    }
}