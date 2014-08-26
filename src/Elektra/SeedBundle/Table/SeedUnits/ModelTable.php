<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\CrudBundle\Definition\Definition;
use Elektra\CrudBundle\Navigator\Navigator;
use Elektra\CrudBundle\Table\Table;

class ModelTable extends Table
{

    protected function initialiseColumns()
    {

        $model = $this->getColumns()->addTitleColumn('table.seed_units.model.name');
        $model->setFieldData(array('name', 'description'));
        $model->setSearchable();
        $model->setSortable();
        $model->setFieldSort('name');


    }
}