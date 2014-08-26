<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;

class CountColumn extends Column
{

    public function __construct(Columns $columns, $title)
    {

        parent::__construct($columns, $title);
        $this->setType('count');
    }

    public function getDisplayData($entry)
    {

        $method = 'get' . ucfirst($this->getFieldData());
        $return = count($entry->$method());

        return $return;
    }
}