<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CompanyLocationTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('name');
        $name->setFieldData('shortName');
        $name->setSearchable();
        $name->setSortable()->setFieldSort('shortName');

        $country = $this->getColumns()->add('country');
        $country->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address'));
        $country->setFieldData('address.country.name');
        $country->setSearchable();

        $state = $this->getColumns()->add('state');
        $state->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address'));
        $state->setFieldData('address.state');
        $state->setSearchable();

        $city = $this->getColumns()->add('city');
        $city->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address'));
        $city->setFieldData('address.city');
        $city->setSearchable();

        $postal = $this->getColumns()->add('postal_code');
        $postal->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address'));
        $postal->setFieldData('address.postalCode');
        $postal->setSearchable();

        $street1 = $this->getColumns()->add('street');
        $street1->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address'));
        $street1->setFieldData(array('address.street1','address.street2','address.street3'));
        $street1->setSearchable();

        //TODO: render as checkbox column
        $isPrimary = $this->getColumns()->add('is_primary');
        $isPrimary->setFieldData('isPrimary');
        $isPrimary->setSearchable();
        $isPrimary->setSortable()->setFieldSort('isPrimary');
        //TODO: set filterable (true, false)

/*        $addressType = $this->getColumns()->add('address_type');
        $addressType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'AddressType'));
        $addressType->setFieldData('addressType.name');
        $addressType->setSortable();
        $addressType->setFilterable()->setFieldFilter('name');*/
    }
}