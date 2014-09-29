<?php

namespace Elektra\SeedBundle\Definition\SeedUnits;

use Elektra\CrudBundle\Crud\Definition;

class SalesStatusDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'SeedUnits', 'SalesStatus');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('salesStatus');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('salesStatuses');
    }
}