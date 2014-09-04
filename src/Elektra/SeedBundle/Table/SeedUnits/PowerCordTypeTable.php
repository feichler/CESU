<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\CrudBundle\Definition\Definition;
use Elektra\CrudBundle\Navigator\Navigator;
use Elektra\CrudBundle\Table\Table;

class PowerCordTypeTable extends Table
{

    protected function initialiseColumns()
    {

        $model = $this->getColumns()->addTitleColumn('tables.seed_units.power_cord_type.name');
        $model->setFieldData(array('name', 'description'));
        $model->setSearchable();
        $model->setSortable();
        $model->setFieldSort('name');
    }
}