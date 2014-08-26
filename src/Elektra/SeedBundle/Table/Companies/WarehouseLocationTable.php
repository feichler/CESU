<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class WarehouseLocationTable extends Table
{

    protected function initialiseColumns()
    {
        $identifier = $this->getColumns()->addTitleColumn('table.companies.warehouseLocation.locationIdentifier');
        $identifier->setFieldData('locationIdentifier');
        $identifier->setSearchable();
        $identifier->setSortable();

        $shortName = $this->getColumns()->add('table.companies.location.shortName');
        $shortName->setFieldData('shortName');
        $shortName->setSearchable();
        $shortName->setSortable();
    }
}