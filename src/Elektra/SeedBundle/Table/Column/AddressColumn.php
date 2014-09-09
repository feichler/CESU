<?php

namespace Elektra\SeedBundle\Table\Column;

use Elektra\CrudBundle\Table\Column\Column;
use Elektra\CrudBundle\Table\Columns;

class AddressColumn extends Column
{

    public function __construct(Columns $columns, $title = '')
    {

        parent::__construct($columns, $title);
        $this->setType('address');
    }
}