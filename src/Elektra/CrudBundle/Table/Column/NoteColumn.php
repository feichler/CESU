<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\AnnotableInterface;

class NoteColumn extends CountColumn
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'table.columns.note');
        $this->setFieldData('notes');
        $this->setType('note');
    }

//    public function getDisplayData($entry)
//    {
//
//        $return = count($entry->getNotes());
//
//        return $return;
//    }
}