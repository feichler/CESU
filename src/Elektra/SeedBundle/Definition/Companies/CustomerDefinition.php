<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class CustomerDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'Customer');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('customer');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('customers');
        $this->addParent('Elektra','Seed','Companies','Partner');
    }
}