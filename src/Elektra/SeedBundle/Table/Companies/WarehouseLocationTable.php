<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class WarehouseLocationTable extends Table
{

    protected function initialiseColumns()
    {
        $identifier = $this->getColumns()->addTitleColumn('location_identifier');
        $identifier->setFieldData('locationIdentifier');
        $identifier->setSearchable();
        $identifier->setSortable();

        $shortName = $this->getColumns()->add('short_name');
        $shortName->setFieldData('shortName');
        $shortName->setSearchable();
        $shortName->setSortable();
    }
}