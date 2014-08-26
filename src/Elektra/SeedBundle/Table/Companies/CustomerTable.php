<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CustomerTable extends Table
{

    protected function initialiseColumns()
    {
        $shortName = $this->getColumns()->addTitleColumn('table.companies.company.shortName');
        $shortName->setFieldData('shortName');
        $shortName->setSearchable();
        $shortName->setSortable();

        $name = $this->getColumns()->add('table.companies.company.name');
        $name->setFieldData('name');
        $name->setSearchable();
        $name->setSortable();
    }
}