<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;
use Elektra\SeedBundle\Table\Column\ContactInfoValueColumn;

class ContactInfoTable extends Table
{

    protected function initialiseColumns()
    {
        $column = new ContactInfoValueColumn($this->getColumns(),'text');
        $text = $this->getColumns()->addColumn($column, null);
//        $text = $this->getColumns()->addTitleColumn('text');
        $text->setFieldData("text");
        $text->setSortable();
        $text->setSearchable();

        $name = $this->getColumns()->add('name');
        $name->setFieldData('name');
        $name->setSortable();
        $name->setSearchable();

        $contactInfoType = $this->getColumns()->add('contact_info_type');
        $contactInfoType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType'));
        $contactInfoType->setFieldData('contactInfoType.name');
        $contactInfoType->setSortable();
        $contactInfoType->setFilterable()->setFieldFilter('name');
    }
}