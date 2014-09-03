<?php

namespace Elektra\SeedBundle\Definition\Events;

use Elektra\CrudBundle\Crud\Definition;

class UnitStatusDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Events', 'UnitStatus');
    }
}