<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CustomerTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $customer = $this->getColumns()->addTitleColumn('tables.companies.company.name');
        $customer->setFieldData(array('shortName', 'name'));
        $customer->setSearchable();
        $customer->setSortable()->setFieldSort('shortName');
    }
}