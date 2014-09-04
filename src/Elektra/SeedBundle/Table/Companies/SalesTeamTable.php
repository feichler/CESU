<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class SalesTeamTable extends Table
{

    protected function initialiseColumns()
    {
        $partner = $this->getColumns()->addTitleColumn('name');
        $partner->setFieldData(array('shortName', 'name'));
        $partner->setSearchable();
        $partner->setSortable()->setFieldSort('shortName');
    }
}