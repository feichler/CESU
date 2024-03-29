<?php

namespace Elektra\SeedBundle\Table\Notes;

use Elektra\CrudBundle\Table\Table;

class NoteTable extends Table
{

    protected function initialiseActions()
    {

        $this->disallowAction('view');
        $this->disallowAction('edit');
        $this->disallowAction('delete');
    }

    protected function initialiseColumns()
    {

        $title = $this->getColumns()->addTitleColumn('title');
        $title->setFieldData('title');
        $title->setSortable();
        $title->setSearchable();

        $text = $this->getColumns()->add('text');
        $text->setFieldData('text');

        $timestamp = $this->getColumns()->addDateColumn('date');
        $timestamp->setFieldData('timestamp');
    }
}