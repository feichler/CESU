<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class PartnerDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'Partner');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('partner');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('partners');
    }
}