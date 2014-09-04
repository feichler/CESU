<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class NoteColumn extends CountColumn
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'tables.generic.columns.note');
        $this->setFieldData('notes');
        $this->setType('note');
    }
}