<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CompanyPersonTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('tables.companies.company_person.name');
        $name->setFieldData("title");

        $firstName = $this->getColumns()->add('tables.companies.company_person.first_name');
        $firstName->setFieldData('firstName');
        $firstName->setSortable();
        $firstName->setSearchable();

        $lastName = $this->getColumns()->add('tables.companies.company_person.last_name');
        $lastName->setFieldData('lastName');
        $lastName->setSortable();
        $lastName->setSearchable();

        //TODO: render as checkbox column
        $isPrimary = $this->getColumns()->add('tables.companies.company_person.is_primary');
        $isPrimary->setFieldData('isPrimary');
        $isPrimary->setSearchable();
        $isPrimary->setSortable();
        //TODO: set filterable (true, false)

        $salutation = $this->getColumns()->add('tables.companies.company_person.salutation');
        $salutation->setFieldData('salutation');
        $salutation->setSortable();
        $salutation->setSearchable();

        $jobTitle = $this->getColumns()->add('tables.companies.company_person.job_title');
        $jobTitle->setFieldData('jobTitle');
        $jobTitle->setSortable();
        $jobTitle->setSearchable();

    }
}