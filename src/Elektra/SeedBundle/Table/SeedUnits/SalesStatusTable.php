<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\CrudBundle\Table\Table;

class SalesStatusTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('name');
        $name->setFieldData('name');
        $name->setSearchable();
        $name->setSortable();
        $name->setFieldSort('name');

        $abbreviation = $this->getColumns()->add('abbreviation');
        $abbreviation->setFieldData('abbreviation');
        $abbreviation->setSearchable();
        $abbreviation->setSortable();
        $abbreviation->setFieldSort('abbreviation');
    }
}