<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CompanyLocationTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('table.companies.companyLocation.name');
        $name->setFieldData(array('shortName', 'name'));
        $name->setSearchable();
        $name->setSortable()->setFieldSort('shortName');

        //TODO: render as checkbox column
        $isPrimary = $this->getColumns()->add('table.companies.companyLocation.isPrimary');
        $isPrimary->setFieldData('isPrimary');
        $isPrimary->setSearchable();
        $isPrimary->setSortable()->setFieldSort('isPrimary');
        //TODO: set filterable (true, false)

        $addressType = $this->getColumns()->add('table.companies.companyLocation.addressType');
        $addressType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'AddressType'));
        $addressType->setFieldData('addressType.name');
        $addressType->setSortable();
        $addressType->setFilterable()->setFieldFilter('name');
    }
}