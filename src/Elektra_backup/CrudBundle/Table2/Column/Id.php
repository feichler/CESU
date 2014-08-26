<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Column;

class Id extends Column
{

    public function __construct()
    {

        parent::__construct('table.columns.id');
        $this->setDataField('id');
        $this->setSortable();
        $this->setWidth('60px');
    }
}