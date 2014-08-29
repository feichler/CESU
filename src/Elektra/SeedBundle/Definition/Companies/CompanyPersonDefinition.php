<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class CompanyPersonDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'CompanyPerson');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('person');

        // has valid parents
        $this->addParent('Elektra', 'Seed', 'Companies', 'Partner');
        $this->addParent('Elektra', 'Seed', 'Companies', 'Customer');
        $this->addParent('Elektra', 'Seed', 'Companies', 'SalesTeam');
        $this->addParent('Elektra', 'Seed', 'Companies', 'CompanyLocation');
    }
}
