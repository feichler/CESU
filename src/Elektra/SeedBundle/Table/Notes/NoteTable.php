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

        $title = $this->getColumns()->addTitleColumn('tables.notes.note.title');
        $title->setFieldData('title');
        $title->setSortable();
        $title->setSearchable();

        $text = $this->getColumns()->add('tables.notes.note.text');
        $text->setFieldData('text');

        $timestamp = $this->getColumns()->addDateColumn('tables.notes.note.date');
        $timestamp->setFieldData('timestamp');

    }
}