<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class IdColumn extends Column
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'table.columns.id');
        $this->setType('id');
        $this->setFieldData('id');
        $this->setSortable();
    }
}