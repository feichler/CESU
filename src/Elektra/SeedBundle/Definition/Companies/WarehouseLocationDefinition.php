<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class WarehouseLocationDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'WarehouseLocation');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('warehouse');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('warehouses');
    }
}