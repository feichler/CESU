<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class AddressDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'Address');
    }
}