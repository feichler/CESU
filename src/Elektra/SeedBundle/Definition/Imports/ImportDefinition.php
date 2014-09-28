<?php

namespace Elektra\SeedBundle\Definition\Imports;

use Elektra\CrudBundle\Crud\Definition;

class ImportDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Imports', 'Import');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('import');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('imports');
    }
}