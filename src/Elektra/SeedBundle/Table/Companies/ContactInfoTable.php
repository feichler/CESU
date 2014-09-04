<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class ContactInfoTable extends Table
{

    protected function initialiseColumns()
    {
        $text = $this->getColumns()->addTitleColumn('tables.companies.contact_info.text');
        $text->setFieldData("text");
        $text->setSortable();
        $text->setSearchable();

        $name = $this->getColumns()->add('tables.companies.contact_info.name');
        $name->setFieldData('name');
        $name->setSortable();
        $name->setSearchable();

        $contactInfoType = $this->getColumns()->add('tables.companies.contact_info.contact_info_type');
        $contactInfoType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType'));
        $contactInfoType->setFieldData('contactInfoType.name');
        $contactInfoType->setSortable();
        $contactInfoType->setFilterable()->setFieldFilter('name');
    }
}