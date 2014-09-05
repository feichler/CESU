<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class GenericLocationDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'GenericLocation');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('location');
    }
}
