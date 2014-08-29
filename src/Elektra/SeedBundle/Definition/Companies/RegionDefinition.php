<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class RegionDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'Region');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('region');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('regions');
    }
}