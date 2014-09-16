<?php

namespace Elektra\SeedBundle\Definition\Events;

use Elektra\CrudBundle\Crud\Definition;

class UnitSalesStatusDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Events', 'UnitSalesStatus');
    }
}