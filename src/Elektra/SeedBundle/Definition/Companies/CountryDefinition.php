<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class CountryDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'Country');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('country');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('countries');

        // has valid parents
        $this->addParent('Elektra', 'Seed', 'Companies', 'Region');
    }
}