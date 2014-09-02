<?php

namespace Elektra\SeedBundle\Definition\SeedUnits;

use Elektra\CrudBundle\Crud\Definition;

class SeedUnitDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('seedUnit');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('seedUnits');

        // has valid parents
        $this->addParent('Elektra', 'Seed', 'Requests', 'Request');
    }
}