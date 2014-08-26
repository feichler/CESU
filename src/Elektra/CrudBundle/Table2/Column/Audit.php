<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Column;

class Audit extends Column {

    public function __construct()
    {

        parent::__construct('table.columns.audit');
    }
}