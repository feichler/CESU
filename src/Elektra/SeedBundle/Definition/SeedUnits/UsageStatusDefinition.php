<?php

namespace Elektra\SeedBundle\Definition\SeedUnits;

use Elektra\CrudBundle\Crud\Definition;

class UsageStatusDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'SeedUnits', 'UsageStatus');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('usageStatus');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('usageStatuses');
    }
}