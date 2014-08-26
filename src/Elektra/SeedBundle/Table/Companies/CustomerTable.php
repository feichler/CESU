<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CustomerTable extends Table
{

    protected function initialiseColumns()
    {
        $partner = $this->getColumns()->addTitleColumn('table.companies.company.name');
        $partner->setFieldData(array('shortName', 'name'));
        $partner->setSearchable();
        $partner->setSortable()->setFieldSort('shortName');
    }
}