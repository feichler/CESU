<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class SalesTeamDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'SalesTeam');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('salesTeam');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('salesTeams');
    }
}