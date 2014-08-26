<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\AuditableInterface;

class AuditColumn extends Column
{

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'table.columns.audit');
        $this->setType('audit');
    }

    public function getDisplayData($entry)
    {

        $return = array();

        // URGENT: throws an exception -> check this
//        $return['created']  = $entry->getCreationAudit();
//        $return['modified'] = $entry->getLastModifiedAudit();

        return $return;
    }
}