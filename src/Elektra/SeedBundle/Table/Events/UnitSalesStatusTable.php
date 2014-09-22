<?php

namespace Elektra\SeedBundle\Table\Events;

use Elektra\CrudBundle\Table\Table;

class UnitSalesStatusTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('name');
        $name->setFieldData('name');
        $name->setSearchable();
        $name->setSortable();
        $name->setFieldSort('name');

        $abbreviation = $this->getColumns()->add('name');
        $abbreviation->setFieldData('abbreviation');
        $abbreviation->setSearchable();
        $abbreviation->setSortable();
        $abbreviation->setFieldSort('abbreviation');
    }
}