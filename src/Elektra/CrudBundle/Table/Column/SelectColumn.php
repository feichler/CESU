<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class SelectColumn extends Column
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, '');
        $this->setType('select');
    }
}