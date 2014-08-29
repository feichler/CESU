<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class CompanyLocationDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'CompanyLocation');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('location');

        // has valid parents
        $this->addParent('Elektra', 'Seed', 'Companies', 'Partner');
        $this->addParent('Elektra', 'Seed', 'Companies', 'Customer');
        $this->addParent('Elektra', 'Seed', 'Companies', 'SalesTeam');
    }
}
