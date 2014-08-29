<?php

namespace Elektra\SeedBundle\Definition\Requests;

use Elektra\CrudBundle\Crud\Definition;

class RequestDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Requests', 'Request');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('request');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('requests');
    }
}