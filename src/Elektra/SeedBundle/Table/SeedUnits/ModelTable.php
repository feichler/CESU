<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\CrudBundle\Table\Table;

class ModelTable extends Table
{

    protected function initialiseColumns()
    {

        $model = $this->getColumns()->addTitleColumn('name');
        $model->setFieldData(array('name', 'description'));
        $model->setSearchable();
        $model->setSortable();
        $model->setFieldSort('name');
    }
}