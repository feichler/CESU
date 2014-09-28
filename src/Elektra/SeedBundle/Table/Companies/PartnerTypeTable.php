<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class PartnerTypeTable extends Table
{

    protected function initialiseColumns()
    {

        $alias = $this->getColumns()->add('alias');
        $alias->setFieldData('alias');
        $alias->setSearchable();
        $alias->setSortable();

        $name = $this->getColumns()->addTitleColumn('name');
        $name->setFieldData('name');
        $name->setSearchable();
        $name->setSortable();
    }
}