<?php

namespace Elektra\SeedBundle\Definition\SeedUnits;

use Elektra\CrudBundle\Crud\Definition;

class PowerCordTypeDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'SeedUnits', 'PowerCordType');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('seedUnitPowerCordType');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('seedUnitPowerCordTypes');
    }
}