<?php

namespace Elektra\SeedBundle\Definition\SeedUnits;

use Elektra\CrudBundle\Definition\Definition;

class ModelDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'SeedUnits', 'Model');
    }
}