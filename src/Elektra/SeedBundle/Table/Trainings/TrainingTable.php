<?php

namespace Elektra\SeedBundle\Table\Trainings;

use Elektra\CrudBundle\Table\Table;

class TrainingTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $training = $this->getColumns()->addTitleColumn('table.trainings.training.training');
        $training->setFieldData('name');
        $training->setSearchable();
        $training->setSortable();

        $start = $this->getColumns()->addDateColumn('table.trainings.training.start');
        $start->setFieldData('startedAt');
        $start->setSortable();

        $end = $this->getColumns()->addDateColumn('table.trainings.training.end');
        $end->setFieldData('endedAt');
        $end->setSortable();

        $registrations = $this->getColumns()->addCountColumn('table.trainings.training.registrations');
        $registrations->setFieldData('registrations');
        $registrations->setSortable();

        $attendances = $this->getColumns()->addCountColumn('table.trainings.training.attendances');
        $attendances->setFieldData('attendances');
        $attendances->setSortable();
    }
}