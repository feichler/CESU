<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class ContactInfoTable extends Table
{

    protected function initialiseColumns()
    {
        $text = $this->getColumns()->addTitleColumn('table.companies.contactInfo.text');
        $text->setFieldData("text");
        $text->setSortable();
        $text->setSearchable();

        $name = $this->getColumns()->add('table.companies.contactInfo.name');
        $name->setFieldData('name');
        $name->setSortable();
        $name->setSearchable();

        $contactInfoType = $this->getColumns()->add('table.companies.contactInfo.contactInfoType');
        $contactInfoType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType'));
        $contactInfoType->setFieldData('contactInfoType.name');
        $contactInfoType->setSortable();
        $contactInfoType->setFilterable()->setFieldFilter('name');
    }
}