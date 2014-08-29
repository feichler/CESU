<?php

namespace Elektra\SeedBundle\Definition\Trainings;

use Elektra\CrudBundle\Crud\Definition;

class RegistrationDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Trainings', 'Registration');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('registration');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('registrations');
    }
}