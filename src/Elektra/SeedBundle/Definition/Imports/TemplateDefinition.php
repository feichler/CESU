<?php

namespace Elektra\SeedBundle\Definition\Imports;

use Elektra\CrudBundle\Crud\Definition;

class TemplateDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Imports', 'Template');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('importTemplate');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('importTemplates');
    }
}