<?php

namespace Elektra\SeedBundle\Definition\Imports;

use Elektra\CrudBundle\Crud\Definition;

class SeedUnitDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Imports', 'SeedUnit');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('importSeedUnit');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('importSeedUnits');
    }
}