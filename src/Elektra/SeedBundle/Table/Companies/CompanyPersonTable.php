<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CompanyPersonTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('table.companies.person.name');
        $name->setFieldData("title");

        $firstName = $this->getColumns()->add('table.companies.person.firstName');
        $firstName->setFieldData('firstName');
        $firstName->setSortable();
        $firstName->setSearchable();

        $lastName = $this->getColumns()->add('table.companies.person.lastName');
        $lastName->setFieldData('lastName');
        $lastName->setSortable();
        $lastName->setSearchable();

        //TODO: render as checkbox column
        $isPrimary = $this->getColumns()->add('table.companies.person.isPrimary');
        $isPrimary->setFieldData('isPrimary');
        $isPrimary->setSearchable();
        $isPrimary->setSortable();
        //TODO: set filterable (true, false)

        $salutation = $this->getColumns()->add('table.companies.person.salutation');
        $salutation->setFieldData('salutation');
        $salutation->setSortable();
        $salutation->setSearchable();

        $jobTitle = $this->getColumns()->add('table.companies.person.salutation');
        $jobTitle->setFieldData('jobTitle');
        $jobTitle->setSortable();
        $jobTitle->setSearchable();

    }
}