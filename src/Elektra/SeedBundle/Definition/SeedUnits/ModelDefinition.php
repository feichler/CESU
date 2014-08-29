<?php

namespace Elektra\SeedBundle\Definition\SeedUnits;

use Elektra\CrudBundle\Crud\Definition;

class ModelDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'SeedUnits', 'Model');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('seedUnitModel');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('seedUnitModels');
    }
}