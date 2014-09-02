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
        $title = $this->getColumns()->addTitleColumn('table.notes.note.title');
        $title->setFieldData('title');
        $title->setSortable();
        $title->setSearchable();

        $text = $this->getColumns()->add('table.notes.note.text');
        $text->setFieldData('text');

        $timestamp = $this->getColumns()->addDateColumn('table.notes.note.date');
        $timestamp->setFieldData('timestamp');



//        $name = $this->getColumns()->add('table.Notes.Note.name');
//        $name->setFieldData('name');
//        $name->setSortable();
//        $name->setSearchable();

//        $NoteType = $this->getColumns()->add('table.Notes.Note.NoteType');
//        $NoteType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Notes', 'NoteType'));
//        $NoteType->setFieldData('NoteType.name');
//        $NoteType->setSortable();
//        $NoteType->setFilterable()->setFieldFilter('name');
    }
}