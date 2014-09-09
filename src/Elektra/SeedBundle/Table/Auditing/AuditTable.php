<?php

namespace Elektra\SeedBundle\Table\Auditing;

use Elektra\CrudBundle\Table\Table;

class AuditTable extends Table
{

    protected function initialiseActions()
    {

        $this->disallowAction('add');
        $this->disallowAction('view');
        $this->disallowAction('edit');
        $this->disallowAction('delete');
    }

    protected function initialiseColumns()
    {

        $title = $this->getColumns()->addTitleColumn('user');
        $title->setFieldData('user.username');
        $title->setSortable();
        $title->setSearchable();

        $timestamp = $this->getColumns()->addDateColumn('date');
        $timestamp->setFieldData('timestamp');
    }
}