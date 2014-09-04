<?php

namespace Elektra\SeedBundle\Table\Trainings;

use Elektra\CrudBundle\Table\Table;

class AttendanceTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $registrant = $this->getColumns()->addTitleColumn('tables.trainings.attendance.attendee');
        $registrant->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
        $registrant->setFieldData('person.lastName');
        $registrant->setSearchable();
        $registrant->setSortable();

        $training = $this->getColumns()->addTitleColumn('tables.trainings.attendance.training');
        $training->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Trainings', 'Training'));
        $training->setFieldData('training.name');
        $training->setFilterable();
        $training->setSearchable();
        $training->setSortable();
    }
}