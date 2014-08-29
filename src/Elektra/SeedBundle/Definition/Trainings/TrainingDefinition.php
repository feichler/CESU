<?php

namespace Elektra\SeedBundle\Definition\Trainings;

use Elektra\CrudBundle\Crud\Definition;

class TrainingDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Trainings', 'Training');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('training');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('trainings');
    }
}