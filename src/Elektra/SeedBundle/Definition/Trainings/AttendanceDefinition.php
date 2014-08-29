<?php

namespace Elektra\SeedBundle\Definition\Trainings;

use Elektra\CrudBundle\Crud\Definition;

class AttendanceDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Trainings', 'Attendance');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('attendance');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('attendances');
    }
}